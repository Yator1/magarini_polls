<?php

namespace App\Http\Controllers;

use App\Models\MalavaParticipant;
use App\Models\User;
use App\Models\Role;
use App\Models\SubCounty;
use App\Models\Ward;
use App\Models\PollingStation;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MalavaParticipantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['overallDashboard']);
    }

    private function participantRoleIds()
    {
        return [4]; // Example roles
    }

    public function index(Request $request)
    {
        $subCounties = SubCounty::where('county_id', 14)->get();
        $wards = Ward::all();
        $markets = Market::all();
        $roles = Role::whereIn('id', $this->participantRoleIds())->get();
        $userTypes = ['Participant', 'Chair']; // Adjusted

        return view('pages.participants.index', [
            'pagename' => 'Malava Participants',
            'subCounties' => $subCounties,
            'wards' => $wards,
            'markets' => $markets,
            'roles' => $roles,
            'userTypes' => $userTypes,
        ]);
    }

    public function filter(Request $request)
    {
        $query = MalavaParticipant::whereIn('role_id', $this->participantRoleIds())
            ->with(['role', 'market', 'ward', 'subCounty']); 

        if (auth()->user()->role_id == 6) {
            $query->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('created_by', auth()->id())
                        ->orWhereNull('created_by');
                })->where(function ($q2) {
                    $q2->where('called_by', auth()->id())
                        ->orWhereNull('called_by');
                });
            });
        }

        if ($request->user_type) $query->where('user_type', $request->user_type);
        if ($request->role_id) $query->where('role_id', $request->role_id);
        if ($request->gender) $query->where('gender', $request->gender);
        if ($request->ward_id) $query->where('ward_id', $request->ward_id);
        if ($request->market_id) $query->where('market_id', $request->market_id);

        if ($request->min_age || $request->max_age) {
            $now = Carbon::now();
            if ($request->min_age) $query->where('date_of_birth', '<=', $now->copy()->subYears($request->min_age)->format('Y-m-d'));
            if ($request->max_age) $query->where('date_of_birth', '>=', $now->copy()->subYears($request->max_age + 1)->addDay()->format('Y-m-d'));
        }

        return response()->json($query->get());
    }

    public function getSuperAgent($id)
    {
        try {
            $agent = MalavaParticipant::with(['subCounty', 'ward'])->find($id);
            if (!$agent) return response()->json(['error' => 'Super agent not found', 'id' => $id], 404);

            return response()->json([
                'fname' => $agent->fname,
                'phone_no' => $agent->phone_number,
                'id_passport_no' => $agent->id_passport_no,
                'age' => Carbon::parse($agent->date_of_birth)->age ?? null,
                'sub_county_name' => optional($agent->subCounty)->name,
                'ward_name' => optional($agent->ward)->name,
                'polling_station_name' => optional($agent->pollingStation)->name,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getSuperAgent(): ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function create()
    {
        $roles = Role::whereIn('id', $this->participantRoleIds())->get();
        $subCounties = SubCounty::where('county_id', 14)->get();
        $markets = Market::all();
        $pollingStations = PollingStation::all();
        $superAgents = MalavaParticipant::all();

        return view('pages.participants.create', [
            'pagename' => 'Create Malava Participant',
            'roles' => $roles,
            'super_agents' => $superAgents,
            'subCounties' => $subCounties,
            'markets' => $markets,
            'pollingStations' => $pollingStations,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'fname' => 'required|string|max:100',
                'id_passport_no' => 'required|string|max:50',
                'phone_no' => 'required|string|max:20',
                'gender' => 'required|in:M,F',
                'sub_county_id' => 'required|exists:sub_counties,id',
                'ward_id' => 'required|exists:wards,id',
            ]);

            if ($request->super_agent === 'not_in_list') {
                $validator = Validator::make($request->all(), [
                    'sa_fname' => 'required|string|max:100',
                    'sa_id_passport_no' => 'required|string|max:50',
                    'sa_phone_number' => 'required|string|max:20',
                    'sa_gender' => 'required|in:M,F',
                ]);

                if ($validator->fails()) return back()->withErrors($validator)->withInput();

                $superAgent = MalavaParticipant::create([
                    'fname' => $request->sa_fname,
                    'id_passport_no' => $request->sa_id_passport_no,
                    'phone_no' => $request->sa_phone_number,
                    'gender' => $request->sa_gender,
                    'county_id' => 14,
                    'role_id' => 9,
                    'created_by' => auth()->id(),
                ]);
                $superAgentId = $superAgent->id;
            } else {
                $superAgentId = $request->super_agent;
            }

            MalavaParticipant::create([
                'fname' => $request->fname,
                'mname' => $request->mname,
                'sname' => $request->sname,
                'id_passport_no' => $request->id_passport_no,
                'phone_no' => $request->phone_number,
                'phone_no_2' => $request->phone_no_2,
                'phone_no_3' => $request->phone_no_3,
                'phone_no_4' => $request->phone_no_4,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'sub_county_id' => $request->sub_county_id,
                'ward_id' => $request->ward_id,
                'polling_station_id' => $request->polling_station_id,
                'role_id' => $request->role_id,
                'user_type' => $request->user_type,
                'created_by' => auth()->id(),
                'called_by' => $superAgentId,
                'county_id' => 14,
            ]);

            return back()->with('success', 'Participant saved successfully!');
        } catch (\Throwable $th) {
            Log::error('MalavaParticipant store error', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ]);
            return back()->withInput()->with('error', 'Something went wrong while saving participant.');
        }
    }

    public function show(MalavaParticipant $participant)
    {
        return view('pages.participants.show', compact('participant'));
    }

    public function edit(MalavaParticipant $participant)
    {
        $roles = Role::whereIn('id', $this->participantRoleIds())->get();
        $subCounties = SubCounty::where('county_id', 14)->get();
        $markets = Market::all();
        $pollingStations = PollingStation::all();
        $super_agents = MalavaParticipant::all();

        return view('pages.participants.edit', compact(
            'participant', 'roles', 'subCounties', 'markets', 'pollingStations', 'super_agents'
        ));
    }

    public function update(Request $request, MalavaParticipant $participant)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|max:100',
            'gender' => 'nullable|in:M,F',
            'role_id' => 'required|in:' . implode(',', $this->participantRoleIds()),
            'sub_county_id' => 'required|exists:sub_counties,id',
            'ward_id' => 'nullable|exists:wards,id',
            'user_type' => 'nullable|in:Participant,Chair',
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        try {
            DB::beginTransaction();

            $participant->update([
                'fname' => $request->fname,
                'mname' => $request->mname,
                'sname' => $request->sname,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'id_passport_no' => $request->id_passport_no,
                'phone_no' => $request->phone_number,
                'phone_no_2' => $request->phone_no_2,
                'phone_no_3' => $request->phone_no_3,
                'phone_no_4' => $request->phone_no_4,
                'role_id' => $request->role_id,
                'sub_county_id' => $request->sub_county_id,
                'ward_id' => $request->ward_id,
                'polling_station_id' => $request->polling_station_id,
                'user_type' => $request->user_type,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('participants.index')->with('success', 'Participant updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating participant: ' . $e->getMessage());
            return back()->with('error', 'Error updating participant: ' . $e->getMessage());
        }
    }

    public function destroy(MalavaParticipant $participant)
    {
        try {
            $participant->delete();
            return redirect()->route('participants.index')->with('success', 'Participant deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting participant: ' . $e->getMessage());
            return back()->with('error', 'Error deleting participant: ' . $e->getMessage());
        }
    }

    public function getWards($subCountyId)
    {
        return response()->json(Ward::where('sub_county_id', $subCountyId)->get(['id', 'name']));
    }

    public function getMarketDetails($marketId)
    {
        $market = Market::find($marketId);
        return response()->json([
            'sub_county_id' => $market?->subcounty_id,
            'ward_id' => $market?->ward_id,
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
            ->leftJoin('mobilizers', 'sub_counties.id', '=', 'mobilizers.sub_county_id')
            ->where('mobilizers.role_id', $roleId)
            ->whereBetween('mobilizers.created_at', [$date1, $date2])
            ->groupBy('sub_counties.id', 'sub_counties.name')
            ->selectRaw(
                'SUM(CASE WHEN mobilizers.gender = "M" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN mobilizers.gender = "F" THEN 1 ELSE 0 END) as female,
                 SUM(CASE WHEN mobilizers.gender IS NULL THEN 1 ELSE 0 END) as unknown,
                 COUNT(mobilizers.id) as total'
            )
            ->get();

        // Ward data
        $wardData = Ward::select('wards.id', 'wards.name', 'sub_counties.name as subcounty_name')
            ->leftJoin('sub_counties', 'wards.subcounty_id', '=', 'sub_counties.id')
            ->leftJoin('mobilizers', 'wards.id', '=', 'mobilizers.ward_id')
            ->where('mobilizers.role_id', $roleId)
            ->whereBetween('mobilizers.created_at', [$date1, $date2])
            ->groupBy('wards.id', 'wards.name', 'sub_counties.name')
            ->selectRaw(
                'SUM(CASE WHEN mobilizers.gender = "M" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN mobilizers.gender = "F" THEN 1 ELSE 0 END) as female,
                 SUM(CASE WHEN mobilizers.gender IS NULL THEN 1 ELSE 0 END) as unknown,
                 COUNT(mobilizers.id) as total'
            )
            ->get();

        // Polling Station data
        $pollingStationData = PollingStation::select('polling_stations.id', 'polling_stations.name', 'wards.name as ward_name')
            ->leftJoin('wards', 'polling_stations.ward_id', '=', 'wards.id')
            ->leftJoin('mobilizers', 'polling_stations.id', '=', 'mobilizers.pstation_code')
            ->where('mobilizers.role_id', $roleId)
            ->whereBetween('mobilizers.created_at', [$date1, $date2])
            ->groupBy('polling_stations.id', 'polling_stations.name', 'wards.name')
            ->selectRaw(
                'SUM(CASE WHEN mobilizers.gender = "M" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN mobilizers.gender = "F" THEN 1 ELSE 0 END) as female,
                 SUM(CASE WHEN mobilizers.gender IS NULL THEN 1 ELSE 0 END) as unknown,
                 COUNT(mobilizers.id) as total'
            )
            ->get()
            ->map(function ($ps) {
                $ps->ward_name = $ps->ward_name ?? 'N/A';
                return $ps;
            });

        // Market data with chair
        $marketData = Market::select('markets.id', 'markets.name as market_name', 'wards.name as ward_name')
            ->leftJoin('wards', 'markets.ward_id', '=', 'wards.id')
            ->leftJoin('mobilizers', 'markets.id', '=', 'mobilizers.market_id')
            ->where('mobilizers.role_id', $roleId)
            ->whereBetween('mobilizers.created_at', [$date1, $date2])
            ->groupBy('markets.id', 'markets.name', 'wards.name')
            ->selectRaw(
                'SUM(CASE WHEN mobilizers.gender = "M" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN mobilizers.gender = "F" THEN 1 ELSE 0 END) as female,
                 SUM(CASE WHEN mobilizers.gender IS NULL THEN 1 ELSE 0 END) as unknown,
                 COUNT(mobilizers.id) as total'
            )
            ->with([
                'mobilizers' => function ($query) use ($roleId, $date1, $date2) {
                    $query->where('user_type', 'Chair')
                        ->where('role_id', $roleId)
                        ->whereBetween('created_at', [$date1, $date2])
                        ->select('mobilizers.id', 'mobilizers.market_id', 'mobilizers.first_name', 'mobilizers.middle_name', 'mobilizers.last_name', 'mobilizers.phone_no');
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
            8 => 'Traders',
            9 => 'Super Agents',
            10 => 'Mobilizers',
            11 => 'Boda Bodas',
            12 => 'Wazee',
            13 => 'Religious',
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
            ->leftJoin('mobilizers', 'sub_counties.id', '=', 'mobilizers.sub_county_id')
            ->whereIn('mobilizers.role_id', array_keys($roleGroups))
            ->whereBetween('mobilizers.created_at', [$date1, $date2])
            ->groupBy('sub_counties.id', 'sub_counties.name')
            ->selectRaw(
                'SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_total`,
                 COUNT(mobilizers.id) as grand_total'
            )
            ->get();

        // Ward data
        $wardData = Ward::select('wards.id', 'wards.name', 'sub_counties.name as subcounty_name')
            ->leftJoin('sub_counties', 'wards.subcounty_id', '=', 'sub_counties.id')
            ->leftJoin('mobilizers', 'wards.id', '=', 'mobilizers.ward_id')
            ->whereIn('mobilizers.role_id', array_keys($roleGroups))
            ->whereBetween('mobilizers.created_at', [$date1, $date2])
            ->groupBy('wards.id', 'wards.name', 'sub_counties.name')
            ->selectRaw(
                'SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_total`,
                 COUNT(mobilizers.id) as grand_total'
            )
            ->get();

        // Polling Station data
        $pollingStationData = PollingStation::select('polling_stations.id', 'polling_stations.name', 'wards.name as ward_name')
            ->leftJoin('wards', 'polling_stations.ward_id', '=', 'wards.id')
            ->leftJoin('mobilizers', 'polling_stations.id', '=', 'mobilizers.pstation_code')
            ->whereIn('mobilizers.role_id', array_keys($roleGroups))
            ->whereBetween('mobilizers.created_at', [$date1, $date2])
            ->groupBy('polling_stations.id', 'polling_stations.name', 'wards.name')
            ->selectRaw(
                'SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_total`,
                 COUNT(mobilizers.id) as grand_total'
            )
            ->get()
            ->map(function ($ps) {
                $ps->ward_name = $ps->ward_name ?? 'N/A';
                return $ps;
            });

        // Market data
        $marketData = Market::select('markets.id', 'markets.name as market_name', 'wards.name as ward_name')
            ->leftJoin('wards', 'markets.ward_id', '=', 'wards.id')
            ->leftJoin('mobilizers', 'markets.id', '=', 'mobilizers.market_id')
            ->whereIn('mobilizers.role_id', array_keys($roleGroups))
            ->whereBetween('mobilizers.created_at', [$date1, $date2])
            ->groupBy('markets.id', 'markets.name', 'wards.name')
            ->selectRaw(
                'SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 8 THEN 1 ELSE 0 END) as `8_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 9 THEN 1 ELSE 0 END) as `9_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 10 THEN 1 ELSE 0 END) as `10_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 11 THEN 1 ELSE 0 END) as `11_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 12 THEN 1 ELSE 0 END) as `12_total`,
                 SUM(CASE WHEN mobilizers.gender = "M" AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_male`,
                 SUM(CASE WHEN mobilizers.gender = "F" AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_female`,
                 SUM(CASE WHEN mobilizers.gender IS NULL AND mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_unknown`,
                 SUM(CASE WHEN mobilizers.role_id = 13 THEN 1 ELSE 0 END) as `13_total`,
                 COUNT(mobilizers.id) as grand_total'
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
