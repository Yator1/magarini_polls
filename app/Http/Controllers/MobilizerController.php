<?php

namespace App\Http\Controllers;

use App\Models\Mobilizer;
use App\Models\User;
use App\Models\Market;
use App\Models\Role;
use App\Models\SubCounty;
use App\Models\MalavaParticipant;
use App\Models\Ward;
use App\Models\PollingStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MobilizerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['overallDashboard']);
    }


    private function mobilizerRoleIds()
    {
        return [4]; // Traders, Super Agents, Mobilizers, Boda Bodas, Wazee, Religious
    }
    // Index: List all mobilizers (users with specific roles), with filters via AJAX
    public function index(Request $request)
    {
        // if (!Gate::allows('canViewMobilizers') && !Gate::allows('canManageMobilizers') && !Gate::allows('isMobilizer')) {
        //     abort(403);
        // }

        // For isMobilizer, redirect to their show page
        // if (Gate::allows('isMobilizer') && !Gate::allows('canViewMobilizers')) {
        //     return redirect()->route('malava_participants.show', Auth::id());
        // }

        $subCounties = SubCounty::where('county_id', 37)->get();
        $wards = Ward::all();
        $markets = Market::all();
        $roles = Role::whereIn('id', $this->mobilizerRoleIds())->get();
        $userTypes = ['Mobilizer', 'Chair']; // As per specs

        $data = [
            'pagename' => 'malava_participants',
            'subCounties' => $subCounties,
            'wards' => $wards,
            'markets' => $markets,
            'roles' => $roles,
            'userTypes' => $userTypes,
        ];

        return view('pages.mobilizers.index', $data);
    }

    // AJAX filter method for index table data
    public function filter(Request $request)
    {
        // if (!Gate::allows('canViewMobilizers') && !Gate::allows('canManageMobilizers')) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        $query = MalavaParticipant::whereIn('role_id', $this->mobilizerRoleIds())
                     ->with(['role', 'market', 'ward', 'subCounty']); 
                     // Eager load relations
        if (auth()->user()->role_id == 6) {
        $query->where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('created_by', auth()->id())
                ->orWhereNull('created_by');
            })
            ->where(function ($q2) {
                $q2->where('called_by', auth()->id())
                ->orWhereNull('called_by');
            });
        });
    }

        // Apply filters
        if ($request->user_type) {
            $query->where('user_type', $request->user_type);
        }
        if ($request->role_id) {
            $query->where('role_id', $request->role_id);
        }
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }
        if ($request->ward_id) {
            $query->where('ward_id', $request->ward_id);
        }
        if ($request->market_id) {
            $query->where('market_id', $request->market_id);
        }
        // Age filter (from d_o_b, assuming d_o_b is date string like YYYY-MM-DD)
        if ($request->min_age || $request->max_age) {
            $now = Carbon::now();
            if ($request->min_age) {
                $query->where('d_o_b', '<=', $now->copy()->subYears($request->min_age)->format('Y-m-d'));
            }
            if ($request->max_age) {
                $query->where('d_o_b', '>=', $now->copy()->subYears($request->max_age + 1)->addDay()->format('Y-m-d'));
            }
        }

        // Other filters if needed (e.g., pstation_code, etc.)

        $mobilizers = $query->get();

        // Return JSON for AJAX to populate table
        return response()->json($mobilizers);
    }
    public function getSuperAgent($id)
    {
        try {
            Log::info("Fetching Super Agent Info for ID: {$id}");

            $agent = MalavaParticipant::with(['SubCounty', 'Ward'])->find($id);

            if (!$agent) {
                Log::warning("Super Agent not found for ID: {$id}");
                return response()->json([
                    'error' => 'Super agent not found',
                    'id' => $id
                ], 404);
            }

            $response = [
                'first_name' => $agent->first_name,
                'phone_no' => $agent->phone_no,
                'id_no' => $agent->id_no,
                'age' => $agent->age,
                'sub_county_name' => optional($agent->SubCounty)->name,
                'ward_name' => optional($agent->Ward)->name,
                'polling_station_name' => optional($agent->pollingstation),
            ];

            Log::info("Super Agent data fetched successfully", $response);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error in getSuperAgent(): ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'id' => $id
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


   public function create()
    {
        $roles = Role::whereIn('id', $this->mobilizerRoleIds())->get();
        $subCounties = SubCounty::where('county_id', 37)->get();
        $markets = Market::all();
        $pollingStations = PollingStation::all();
        $superAgents = MalavaParticipant::all();

        $data = [
            'pagename' => 'Create Mobilizer',
            'roles' => $roles,
            'super_agents' => $superAgents,
            'subCounties' => $subCounties,
            'markets' => $markets,
            'pollingStations' => $pollingStations,
        ];

        return view('pages.mobilizers.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            // Step 1: Main validation
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'id_no' => 'required|numeric',
                'phone_no' => 'required|string|max:20',
                'age' => 'nullable|numeric',
                'gender' => 'required',
                'pstation_code' => 'required',
                'super_agent' => 'nullable|string',
                'sa_name' => 'nullable|string',
                'sa_id' => 'nullable|numeric',
                'sa_phone' => 'nullable|string',
                'sa_age' => 'nullable|numeric',
                'sub_county_id' => 'required|exists:sub_counties,id',
                'ward_id' => 'required|exists:wards,id',
            ]);

            // Step 2: Polling Station
            if ($request->pstation_code === 'not_in_list') {
                $validator = Validator::make($request->all(), [
                    'new_polling_station' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                $pollingStation = PollingStation::create([
                    'name' => $request->new_polling_station,
                    'ward_id' => $request->ward_id,
                ]);
                $pollingStationId = $pollingStation->id;
            } else {
                $pollingStationId = $request->pstation_code;
            }

            // Step 3: Super Agent
            if ($request->super_agent === 'not_in_list') {
                $validator = Validator::make($request->all(), [
                    'sa_name' => 'required|string|max:255',
                    'sa_id' => 'required|numeric',
                    'sa_phone' => 'required|string|max:20',
                    'sa_gender' => 'required|string',
                ]);


                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                $superAgent = MalavaParticipant::create([
                    'first_name' => $request->sa_name,
                    'id_no' => $request->sa_id,
                    'phone_no' => $request->sa_phone,
                    'age' => $request->sa_age,
                    'sub_county_id' => $request->sa_subcounty ?? $request->sub_county_id,
                    'ward_id' => $request->sa_ward ?? $request->ward_id,
                    'gender' => $request->sa_gender,
                    'county_id' => 37,
                    'role_id' => 9, // Super Agent
                    'created_by' => auth()->id(),
                ]);
                $superAgentId = $superAgent->id;
            } else {
                $superAgentId = $request->super_agent;
            }

            // Step 4: Save Mobilizer
            MalavaParticipant::create([
                'first_name' => $request->first_name,
                'id_no' => $request->id_no,
                'phone_no' => $request->phone_no,
                'age' => $request->age,
                'gender' => $request->gender,
                'sub_county_id' => $request->sub_county_id,
                'ward_id' => $request->ward_id,
                'pstation_code' => $pollingStationId,
                'super_agent' => $superAgentId,
                'county_id' => 37,
                'role_id' => $request->role_id, 
                'created_by' => auth()->id(),
            ]);

            return back()->with('success', 'Mobilizer saved successfully!');
        } catch (\Throwable $th) {

        dd($th->getMessage(), $th->getFile(), $th->getLine());
            Log::error('Mobilizer store error', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ]);
            return back()->withInput()->with('error', 'Something went wrong while saving mobilizer.');
        }
    }



    public function show(MalavaParticipant $mobilizer)
    {
        // if (!Gate::allows('canViewMobilizers') && !Gate::allows('canManageMobilizers') && !(Gate::allows('isMobilizer') && Auth::id() === $mobilizer->id)) {
        //     abort(403);
        // }

        if (!in_array($mobilizer->role_id, $this->mobilizerRoleIds())) {
            abort(404);
        }

        return view('pages.mobilizers.show', compact('mobilizer'));
    }

    public function edit(MalavaParticipant $mobilizer)
    {
        // if (!Gate::allows('canManageMobilizers')) {
        //     abort(403);
        // }

        if (!in_array($mobilizer->role_id, $this->mobilizerRoleIds())) {
            abort(404);
        }

        $roles = Role::whereIn('id', $this->mobilizerRoleIds())->get();
        $subCounties = SubCounty::where('county_id', 37)->get();
        $markets = Market::all();
        $pollingStations = PollingStation::all();
        // $super_agents = MalavaParticipant::where('role_id', 9)->get();
        $super_agents = MalavaParticipant::all();

        $data = [
            'pagename' => 'Edit Mobilizer',
            'mobilizer' => $mobilizer,
            'roles' => $roles,
            'super_agents' => $super_agents,
            'subCounties' => $subCounties,
            'markets' => $markets,
            'pollingStations' => $pollingStations,
        ];

        return view('pages.mobilizers.edit', $data);
    }

    public function update(Request $request, Mobilizer $mobilizer)
    {
        // if (!Gate::allows('canManageMobilizers')) {
        //     abort(403);
        // }

        if (!in_array($mobilizer->role_id, $this->mobilizerRoleIds())) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:191',
            // 'middle_name' => 'nullable|string|max:191',
            'age' => 'nullable',
            // 'last_name' => 'string|max:191',
            'super_agent' => 'nullable|exists:mobilizers,id',
            'id_no' => 'required|date',
            'id_no' => 'string|max:255' . $mobilizer->id,
            'phone_no' => 'required|string|max:255' . $mobilizer->id,
            'role_id' => 'required|in:' . implode(',', $this->mobilizerRoleIds()),
            'sub_county_id' => 'required|exists:sub_counties,id',
            'ward_id' => 'nullable|exists:wards,id',
            'market_id' => 'nullable|exists:markets,id',
            'pstation_code' => 'nullable|exists:polling_stations,id',
            'gender' => 'nullable|in:M,F',
            'user_type' => 'nullable|in:Mobilizer,Chair',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for mobilizer update: ' . $validator->errors()->toJson());
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $mobilizer->first_name = $request->first_name;
            $mobilizer->middle_name = $request->middle_name;
            $mobilizer->last_name = $request->last_name;
            $mobilizer->age = $request->age;
            $mobilizer->super_agent = $request->super_agent;
            $mobilizer->d_o_b = $request->d_o_b;
            $mobilizer->id_no = $request->id_no;
            $mobilizer->phone_no = $request->phone_no;
            $mobilizer->role_id = $request->role_id;
            $mobilizer->county_id = 37;
            $mobilizer->sub_county_id = $request->sub_county_id;
            $mobilizer->ward_id = $request->ward_id;
            $mobilizer->market_id = $request->market_id;
            $mobilizer->pstation_code = $request->pstation_code;
            $mobilizer->gender = $request->gender;
            $mobilizer->user_type = $request->user_type;
            $mobilizer->updated_by = Auth::id();

            $mobilizer->save();

            DB::commit();

            Log::info('Mobilizer updated successfully: ID ' . $mobilizer->id);

            return redirect()->route('malava_participants.index')->with('success', 'Mobilizer updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating mobilizer: ' . $e->getMessage());
            return back()->with('error', 'Error updating mobilizer: ' . $e->getMessage());
        }
    }

    public function destroy(MalavaParticipant $mobilizer)
    {
        if (!Gate::allows('canManageMobilizers')) {
            abort(403);
        }

        if (!in_array($mobilizer->role_id, $this->mobilizerRoleIds())) {
            abort(404);
        }

        try {
            $mobilizer->delete();
            Log::info('Mobilizer deleted successfully: ID ' . $mobilizer->id);
            return redirect()->route('malava_participants.index')->with('success', 'Mobilizer deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting mobilizer: ' . $e->getMessage());
            return back()->with('error', 'Error deleting mobilizer: ' . $e->getMessage());
        }
    }


    public function getWards($subCountyId)
    {
        $wards = Ward::where('sub_county_id', $subCountyId)->get(['id', 'name']);
        return response()->json($wards);
    }

    public function getMarketDetails($marketId)
    {
        $market = Market::find($marketId);
        return response()->json([
            'sub_county_id' => $market ? $market->subcounty_id : null,
            'ward_id' => $market ? $market->ward_id : null
        ]);
    }
   
    public function dashboard($roleId)
    {
        // if (!Gate::allows('canViewMobilizers') && !Gate::allows('canManageMobilizers')) {
        //     abort(403);
        // }

        $role = Role::findOrFail($roleId);
        // if (!in_array($role->id, $this->mobilizerRoleIds())) {
        //     abort(404);
        // }

        // Get date range from request or default to last 30 days
        $date1 = request('date_1')? Carbon::parse(request('date_1')): Carbon::create(2025, 1, 1)->startOfDay();
        $date2 = request('date_2')? Carbon::parse(request('date_2')): Carbon::now()->endOfDay();

        // Summary stats for the role, including null gender
        $summaryStats = MalavaParticipant::where('role_id', $roleId)
            ->whereBetween('created_at', [$date1, $date2])
            ->groupBy('gender')
            ->selectRaw('gender, COUNT(*) as count')
            ->get()
            ->reduce(function ($carry, $item) {
                $key = $item->gender ?? '';
                $carry[$key] = $item->count;
                return $carry;
            }, ['M' => 0, 'F' => 0, '' => 0, 'total' => 0]);
        $summaryStats['total'] = $summaryStats['M'] + $summaryStats['F'] + $summaryStats[''];
            // dd($summaryStats);
        // Subcounty data
        $subcountyData = SubCounty::select('sub_counties.id', 'sub_counties.name')
            ->leftJoin('malava_participants', 'sub_counties.id', '=', 'malava_participants.sub_county_id')
            ->where('malava_participants.role_id', $roleId)
            ->whereBetween('malava_participants.created_at', [$date1, $date2])
            ->groupBy('sub_counties.id', 'sub_counties.name')
            ->selectRaw(
                'SUM(CASE WHEN malava_participants.gender = "M" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN malava_participants.gender = "F" THEN 1 ELSE 0 END) as female,
                 SUM(CASE WHEN malava_participants.gender IS NULL THEN 1 ELSE 0 END) as unknown,
                 COUNT(malava_participants.id) as total'
            )
            ->get();

        // Ward data
        $wardData = Ward::select('wards.id', 'wards.name', 'sub_counties.name as subcounty_name')
            ->leftJoin('sub_counties', 'wards.subcounty_id', '=', 'sub_counties.id')
            ->leftJoin('malava_participants', 'wards.id', '=', 'malava_participants.ward_id')
            ->where('malava_participants.role_id', $roleId)
            ->whereBetween('malava_participants.created_at', [$date1, $date2])
            ->groupBy('wards.id', 'wards.name', 'sub_counties.name')
            ->selectRaw(
                'SUM(CASE WHEN malava_participants.gender = "M" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN malava_participants.gender = "F" THEN 1 ELSE 0 END) as female,
                 SUM(CASE WHEN malava_participants.gender IS NULL THEN 1 ELSE 0 END) as unknown,
                 COUNT(malava_participants.id) as total'
            )
            ->get();

        // Polling Station data
        $pollingStationData = PollingStation::select('polling_stations.id', 'polling_stations.name', 'wards.name as ward_name')
            ->leftJoin('wards', 'polling_stations.ward_id', '=', 'wards.id')
            ->leftJoin('malava_participants', 'polling_stations.id', '=', 'malava_participants.pstation_code')
            ->where('malava_participants.role_id', $roleId)
            ->whereBetween('malava_participants.created_at', [$date1, $date2])
            ->groupBy('polling_stations.id', 'polling_stations.name', 'wards.name')
            ->selectRaw(
                'SUM(CASE WHEN malava_participants.gender = "M" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN malava_participants.gender = "F" THEN 1 ELSE 0 END) as female,
                 SUM(CASE WHEN malava_participants.gender IS NULL THEN 1 ELSE 0 END) as unknown,
                 COUNT(malava_participants.id) as total'
            )
            ->get()
            ->map(function ($ps) {
                $ps->ward_name = $ps->ward_name ?? 'N/A';
                return $ps;
            });

        // Market data with chair
        $marketData = Market::select('markets.id', 'markets.name as market_name', 'wards.name as ward_name')
            ->leftJoin('wards', 'markets.ward_id', '=', 'wards.id')
            ->leftJoin('malava_participants', 'markets.id', '=', 'malava_participants.market_id')
            ->where('malava_participants.role_id', $roleId)
            ->whereBetween('malava_participants.created_at', [$date1, $date2])
            ->groupBy('markets.id', 'markets.name', 'wards.name')
            ->selectRaw(
                'SUM(CASE WHEN malava_participants.gender = "M" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN malava_participants.gender = "F" THEN 1 ELSE 0 END) as female,
                 SUM(CASE WHEN malava_participants.gender IS NULL THEN 1 ELSE 0 END) as unknown,
                 COUNT(malava_participants.id) as total'
            )
            ->with([
                'malava_participants' => function ($query) use ($roleId, $date1, $date2) {
                    $query->where('user_type', 'Chair')
                        ->where('role_id', $roleId)
                        ->whereBetween('created_at', [$date1, $date2])
                        ->select('malava_participants.id', 'malava_participants.market_id', 'malava_participants.first_name', 'malava_participants.middle_name', 'malava_participants.last_name', 'malava_participants.phone_no');
                }
            ])
            ->get()
            ->map(function ($market) {
                $market->ward_name = $market->ward_name ?? 'N/A';
                $chair = $market->mobilizers->first();
                $market->chair_name = $chair ? trim($chair->first_name . ' ' . ($chair->middle_name ?? '') . ' ' . $chair->last_name) : 'N/A';
                $market->chair_phone = $chair ? $chair->phone_no : 'N/A';
                unset($market->mobilizers);
                return $market;
            });

        return view('pages.mobilizers.dashboards.role-dashboard', compact(
            'role',
            'summaryStats',
            'subcountyData',
            'wardData',
            'pollingStationData',
            'marketData',
            'date1',
            'date2'
        ));
    }

    public function overallDashboard(Request $request)
    {


        // if (auth()->check()) {
        //     if (!Gate::allows('canViewMobilizers') && !Gate::allows('canManageMobilizers')) {
        //         abort(403);
        //     }
        // }


        // if (!Gate::allows('canViewMobilizers') && !Gate::allows('canManageMobilizers')) {
        //     abort(403);
        // }

        // Get date range from request or default to last 30 days
        $date1 = request('date_1')? Carbon::parse(request('date_1')): Carbon::create(2025, 1, 1)->startOfDay();
        $date2 = request('date_2')? Carbon::parse(request('date_2')): Carbon::now()->endOfDay();

        $roleGroups = [
            4 => 'Malava Participants',
        ];

        // Cache roles to avoid repeated queries
        $roles = cache()->remember('mobilizer_roles', 3600, function () use ($roleGroups) {
            return Role::whereIn('id', array_keys($roleGroups))->pluck('name', 'id');
        });

        // Summary statistics for cards, per role
        $summaryStats = [];
        $totalMale = 0;
        $totalFemale = 0;
        $totalUnknown = 0;
        $grandTotal = 0;

        // Fetch role stats with a single query, including null gender
        $roleStats = MalavaParticipant::select('role_id', 'gender')
            ->whereIn('role_id', array_keys($roleGroups))
            ->whereBetween('created_at', [$date1, $date2])
            ->groupBy('role_id', 'gender')
            ->selectRaw('COUNT(*) as count')
            ->get()
            ->groupBy('role_id');

        // Initialize summary stats for all roles
        foreach ($roleGroups as $roleId => $roleName) {
            $male = isset($roleStats[$roleId]) ? ($roleStats[$roleId]->where('gender', 'M')->first()->count ?? 0) : 0;
            $female = isset($roleStats[$roleId]) ? ($roleStats[$roleId]->where('gender', 'F')->first()->count ?? 0) : 0;
            $unknown = isset($roleStats[$roleId]) ? ($roleStats[$roleId]->where('gender', null)->first()->count ?? 0) : 0;
            $total = $male + $female + $unknown;
            $summaryStats[$roleId] = [
                'name' => $roleName,
                'male' => $male,
                'female' => $female,
                'unknown' => $unknown,
                'total' => $total,
            ];
            $totalMale += $male;
            $totalFemale += $female;
            $totalUnknown += $unknown;
            $grandTotal += $total;
        }

        // Subcounty data
        $subcountyData = SubCounty::select('sub_counties.id', 'sub_counties.name')
            ->leftJoin('malava_participants', 'sub_counties.id', '=', 'malava_participants.sub_county_id')
            ->whereIn('malava_participants.role_id', array_keys($roleGroups))
            ->whereBetween('malava_participants.created_at', [$date1, $date2])
            ->groupBy('sub_counties.id', 'sub_counties.name')
            ->selectRaw(
                'SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_total`,
                 COUNT(malava_participants.id) as grand_total'
            )
            ->get();

        // Ward data
        $wardData = Ward::select('wards.id', 'wards.name', 'sub_counties.name as subcounty_name')
            ->leftJoin('sub_counties', 'wards.subcounty_id', '=', 'sub_counties.id')
            ->leftJoin('malava_participants', 'wards.id', '=', 'malava_participants.ward_id')
            ->whereIn('malava_participants.role_id', array_keys($roleGroups))
            ->whereBetween('malava_participants.created_at', [$date1, $date2])
            ->groupBy('wards.id', 'wards.name', 'sub_counties.name')
            ->selectRaw(
                'SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_total`,
                 COUNT(malava_participants.id) as grand_total'
            )
            ->get();

        // Polling Station data
        $pollingStationData = PollingStation::select('polling_stations.id', 'polling_stations.name', 'wards.name as ward_name')
            ->leftJoin('wards', 'polling_stations.ward_id', '=', 'wards.id')
            ->leftJoin('malava_participants', 'polling_stations.id', '=', 'malava_participants.pstation_code')
            ->whereIn('malava_participants.role_id', array_keys($roleGroups))
            ->whereBetween('malava_participants.created_at', [$date1, $date2])
            ->groupBy('polling_stations.id', 'polling_stations.name', 'wards.name')
            ->selectRaw(
                'SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_total`,
                 COUNT(malava_participants.id) as grand_total'
            )
            ->get()
            ->map(function ($ps) {
                $ps->ward_name = $ps->ward_name ?? 'N/A';
                return $ps;
            });

        // Market data
        $marketData = Market::select('markets.id', 'markets.name as market_name', 'wards.name as ward_name')
            ->leftJoin('wards', 'markets.ward_id', '=', 'wards.id')
            ->leftJoin('malava_participants', 'markets.id', '=', 'malava_participants.market_id')
            ->whereIn('malava_participants.role_id', array_keys($roleGroups))
            ->whereBetween('malava_participants.created_at', [$date1, $date2])
            ->groupBy('markets.id', 'markets.name', 'wards.name')
            ->selectRaw(
                'SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 8 THEN 1 ELSE 0 END) as `8_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 9 THEN 1 ELSE 0 END) as `9_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 10 THEN 1 ELSE 0 END) as `10_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 11 THEN 1 ELSE 0 END) as `11_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 12 THEN 1 ELSE 0 END) as `12_total`,
                 SUM(CASE WHEN malava_participants.gender = "M" AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_male`,
                 SUM(CASE WHEN malava_participants.gender = "F" AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_female`,
                 SUM(CASE WHEN malava_participants.gender IS NULL AND malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_unknown`,
                 SUM(CASE WHEN malava_participants.role_id = 13 THEN 1 ELSE 0 END) as `13_total`,
                 COUNT(malava_participants.id) as grand_total'
            )
            ->get()
            ->map(function ($market) {
                $market->ward_name = $market->ward_name ?? 'N/A';
                return $market;
            });



        if (auth()->check()) {
                return view('pages.mobilizers.dashboards.overall', compact(
                    'roleGroups',
                    'summaryStats',
                    'subcountyData',
                    'wardData',
                    'pollingStationData',
                    'marketData',
                    'totalMale',
                    'totalFemale',
                    'totalUnknown',
                    'grandTotal',
                    'date1',
                    'date2'
                ));
            }else{
                return view('pages.mobilizers.dashboards.overall_2', compact(
                    'roleGroups',
                    'summaryStats',
                    'subcountyData',
                    'wardData',
                    'pollingStationData',
                    'marketData',
                    'totalMale',
                    'totalFemale',
                    'totalUnknown',
                    'grandTotal',
                    'date1',
                    'date2'
                ));
            }
        

    }
}