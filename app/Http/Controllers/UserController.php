<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\SubCounty;
use App\Models\User;
use App\Models\Mobilizer;
use App\Models\MalavaParticipant;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    //

    public function agentCalls($id){
        $subCounties =  SubCounty::all();
        $poll = Poll::find(3);
        $poll_answers = PollAnswer::where('poll_id', $poll->id)->get();
        $agents = User::where('role_id', 6)->get();
        $agent = User::find($id);
       $type = "";

        $users = MalavaParticipant::
        // ->where('caw','MBIRI')
        whereNotNull('phone_no')
        ->where('phone_no', '!=', '')
        // ->where('user_type', "participants")
            ->where('called_by', $agent->id)
            ->get();
        
        $data = [
            'pagename'=>'All Contacts Of'.$agent->first_name ?? ''.' '. $agent->last_name ?? '',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'users' => $users,
            'poll' => $poll,
            'agents' => $agents,
            'agent' => $agent,
            'type' => $type,
        ];

        // dd($type, $users);
        return view('pages.polls.reports.contacts2', $data);
    }
    public function agentCallsPicked($id){
        
        $subCounties =  SubCounty::where('county_id', '37')->get();
        $poll = Poll::find(3);
        $poll_answers = PollAnswer::where('poll_id', $poll->id)->get();
        $agents = User::where('role_id', 6)->get();
        $agent = User::find($id);
       $type = "";

        $users = MalavaParticipant::where('role_id', 4)
        // ->where('caw','MBIRI')
        ->whereNotNull('phone_no')
        ->where('phone_no', '!=', '')
        // ->where('user_type', "participants")
            ->whereHas('participantAnswers', function ($query) use ($poll) {
                $query->where('poll_id', $poll->id);
            })
            ->where('called_by', $agent->id)
            ->get();
        
        $data = [
            'pagename'=>'All Picked Calls Of '.$agent->first_name ?? ''.' '. $agent->last_name ?? '',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'users' => $users,
            'poll' => $poll,
            'poll_answers' => $poll_answers,
            'agents' => $agents,
            'agent' => $agent,
            'type' => $type,
        ];

        // dd($type, $users);
        return view('pages.polls.reports.participants2', $data);
    }
    public function participants(Request $request){
        ini_set('memory_limit', '1000M'); // Increase memory limit to 512MB
        ini_set('max_execution_time', '3000'); // Increase execution time to 300 seconds


        $date1 = Carbon::today()->startOfDay();
        $date2 = Carbon::today()->endOfDay();
        $poll = Poll::find(3);
        $poll_answers = PollAnswer::where('poll_id', $poll->id)->get();
        $type = "";
        $users = [''];

      


        if ($request->isMethod('get')) {

        $data = [
            'pagename'=>'Participants Report',
            'application_type' => 'member',
            'users' => $users,
            'date1' => $date1,
            'date2' => $date2,
            'poll' => $poll,
            'poll_answers' => $poll_answers,
            'type' => $type,
        ];

        return view('pages.polls.reports.participants', $data);

		} else if ($request->isMethod('post')) {
            // dd($request->all());


            $date1 = Carbon::parse($request->date_1)->startOfDay();
            $date2 = Carbon::parse($request->date_2)->endOfDay();
                // Check if the user is admin
                if (Gate::allows('is_admin')) {
                    $users = MalavaParticipant::where('role_id', 4)
                    // ->where('caw','MBIRI')
                    ->whereNotNull('phone_no')
                    ->where('phone_no', '!=', '')
                        // ->where('user_type', "participants")
                        ->where('call_status', 1)
                        ->whereHas('participantAnswers', function ($query) use ($poll) {
                            $query->where('poll_id', $poll->id);
                        });
                } else {
                    $users = MalavaParticipant::where('role_id', 4)
                    // ->where('caw','MBIRI')
                    ->whereNotNull('phone_no')
                    ->where('phone_no', '!=', '')
                        // ->where('user_type', "participants")
                        ->whereHas('participantAnswers', function ($query) use ($poll) {
                            $query->where('poll_id', $poll->id);
                        })
                        ->where('call_status', 1)
                        ->where('called_by', auth()->user()->id);
                }

                if ($date1 && $date2) {
                    $users->when($request->input('date_1'), function ($query) use ($date1, $date2) {
                        return $query->whereBetween('updated_at', [$date1, $date2]);
                    });
                }
                

                    $users = $users->get();
                


                // dd($request->all(), $users);

            $data = [
                'pagename'=>'Participants Report',
                'application_type' => 'member',
                'users' => $users,
                'date1' => $date1,
                'date2' => $date2,
                'poll' => $poll,
                'poll_answers' => $poll_answers,
                'type' => $type,
            ];

            return view('pages.polls.reports.participants', $data);

        }

    
    }

    
    public function all(){
        $subCounties =  SubCounty::where('county_id', '37')->get();
        $poll = Poll::find(3);
        $agents = User::where('role_id', 6)->get();
        $type = "";

        if (Gate::allows('is_admin')){
        $users = MalavaParticipant::where('role_id', 4)
        // ->where('caw','MBIRI')
        ->whereNotNull('phone_no')
        ->where('phone_no', '!=', '')
        // ->where('user_type', "participants")
            ->paginate(100);
            $type = "needspaginate";
        }else{
            // $users = [];
            $users = MalavaParticipant::where('role_id', 4)
            // ->where('caw','MBIRI')
            ->whereNotNull('phone_no')
            ->where('phone_no', '!=', '')
            // ->where('user_type', "participants")
             ->where(function ($query) {
                 $query->whereNull('called_by')
                       ->orWhere('called_by', auth()->user()->id);
             })
             ->get();

            
        }

        $data = [
            'pagename'=>'All Contacts ',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'users' => $users,
            'poll' => $poll,
            'agents' => $agents,
            'type' => $type,
        ];

        return view('pages.polls.reports.contacts', $data);
    }
    public function all2(Request $request){

        $poll = Poll::find(3);
        $agents = User::where('role_id', 6)->get();
        $type = "";
        ini_set('memory_limit', '256M'); // Increase memory limit as needed



        if ($request->isMethod('get')) {
            // dd('all');
            if (Gate::allows('is_admin')) {
                $users = MalavaParticipant::select('first_name', 'middle_name', 'last_name', 'phone_no', 'phone_no_2', 'phone_no_3', 'phone_no_4', 'email', 'address', 'call_status', 'called_by', 'id')
                    // ->where('role_id', 4)
                    // ->where('caw', 'MBIRI')
                    ->whereNotNull('phone_no')
                    ->where('phone_no', '!=', '')
                    // ->where('user_type', "participants")
                    // ->paginate(100); // Adjust per page count as needed
                    ->get();
            } else {
                $users = MalavaParticipant::select('first_name', 'middle_name', 'last_name', 'phone_no', 'phone_no_2', 'phone_no_3', 'phone_no_4', 'email', 'address', 'call_status', 'called_by', 'id')
                    // ->where('role_id', 4)
                    // ->where('caw', 'MBIRI')
                    ->whereNotNull('phone_no')
                    ->where('phone_no', '!=', '')
                    // ->where('user_type', "participants")
                    ->where(function ($query) {
                        $query->where('called_by', auth()->user()->id);
                    })
                    // ->paginate(100); // Adjust per page count as needed
                    ->get();
            }
            

            // $users = $users->get();

            $data = [
                'pagename'=>'All Contacts ',
                'application_type' => 'member',
                'users' => $users,
                'poll' => $poll,
                'agents' => $agents,
                'type' => $type,
                'oldCounty' => '',
                'oldFacilityLevel' => '',
                'oldFacilityType' => '',
                'oldFacilityAgent' => '',
            ];
            return view('pages.polls.reports.contacts_new', $data);
        

		} else if ($request->isMethod('post')) {
            ini_set('memory_limit', '256M'); // Increase memory limit as needed

            // $validatedData = $request->validate([
            //     'county' => 'required', // Adjust accordingly
            // ]);
                // Check if the user is admin
                if (Gate::allows('is_admin')) {
                    $users = MalavaParticipant::select('first_name', 'middle_name', 'last_name', 'phone_no', 'phone_no_2', 'phone_no_3', 'phone_no_4', 'email', 'address', 'call_status', 'called_by', 'id')
                        // ->where('role_id', 4)
                        // ->where('caw', 'MBIRI')
                        ->whereNotNull('phone_no')
                        ->where('phone_no', '!=', '')
                        // ->where('user_type', "participants")
                        ->get();
                        // ->paginate(100); // Adjust per page count as needed
                } else {
                    $users = MalavaParticipant::select('first_name', 'middle_name', 'last_name', 'phone_no', 'phone_no_2', 'phone_no_3', 'phone_no_4', 'email', 'address', 'call_status', 'called_by', 'id')
                        // ->where('role_id', 4)
                        // ->where('caw', 'MBIRI')
                        ->whereNotNull('phone_no')
                        ->where('phone_no', '!=', '')
                        // ->where('user_type', "participants")
                        ->where(function ($query) {
                            $query->where('called_by', auth()->user()->id);
                        })
                        ->get();
                        // ->paginate(100); // Adjust per page count as needed
                }
                

                // Apply filters based on input values
                // $users->when($request->input('county'), function ($query, $county) {
                //     return $query->where('county', $county);
                // })->when($request->input('FacilityLevel'), function ($query, $FacilityLevel) {
                //     return $query->where('FacilityLevel', $FacilityLevel);
                // })->when($request->input('FacilityType'), function ($query, $FacilityType) {
                //     return $query->where('FacilityType', $FacilityType);
                // })->when($request->input('FacilityAgent'), function ($query, $FacilityAgent) {
                //     return $query->where('FacilityAgent', $FacilityAgent);
                // });

                // Get the final filtered users
                // $users = $users->get();

    

            $data = [
                'pagename'=>'All Contacts ',
                'application_type' => 'member',
                'users' => $users,
                'poll' => $poll,
                'agents' => $agents,
                'type' => $type,
            ];
            return view('pages.polls.reports.contacts_new', $data);
        }
    }
    public function mycalls(Request $request){


        $County = MalavaParticipant::pluck('County')->unique();
        // $FacilityLevel = MalavaParticipant::pluck('FacilityLevel')->unique();
        // $FacilityType = MalavaParticipant::pluck('FacilityType')->unique();
        // $FacilityAgent = MalavaParticipant::pluck('FacilityAgent')->unique();
        $poll = Poll::find(3);
        $agents = User::where('role_id', 6)->get();
        $type = "";

        if ($request->isMethod('get')) {
            $users = [''];
            $data = [
                'pagename'=>'All Contacts ',
                'Counties' => $County,
                'FacilityLevels' => '',
                'FacilityTypes' => '',
                'FacilityAgents' => '',
                'application_type' => 'member',
                'users' => $users,
                'poll' => $poll,
                'agents' => $agents,
                'type' => $type,
                'oldCounty' => '',
                'oldFacilityLevel' => '',
                'oldFacilityType' => '',
                'oldFacilityAgent' => '',
            ];
            return view('pages.polls.reports.contacts_new', $data);
        

		} else if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'county' => 'required', // Adjust accordingly
            ]);
                // Check if the user is admin
                if (Gate::allows('is_admin')) {
                    $users = MalavaParticipant::where('role_id', 4)
                    // ->where('caw','MBIRI')
                    ->whereNotNull('phone_no')
                    ->where('phone_no', '!=', '');
                        // ->where('user_type', "participants");
                } else {
                    $users = MalavaParticipant::where('role_id', 4)
                    // ->where('caw','MBIRI')
                    ->whereNotNull('phone_no')
                    ->where('phone_no', '!=', '')
                        // ->where('user_type', "participants")
                        ->where(function ($query) {
                            $query->whereNull('called_by')
                                ->orWhere('called_by', auth()->user()->id);
                        });
                }

                // Apply filters based on input values
                $users->when($request->input('county'), function ($query, $county) {
                    return $query->where('county', $county);
                });

                // Get the final filtered users
                $users = $users->get();

    

            $data = [
                'pagename'=>'All Contacts ',
                'Counties' => $County,
                'FacilityLevels' => '',
                'FacilityTypes' => '',
                'FacilityAgents' => '',
                'application_type' => 'member',
                'users' => $users,
                'poll' => $poll,
                'agents' => $agents,
                'type' => $type,
                'oldCounty' => $request->input('county'),
                'oldFacilityLevel' => $request->input('FacilityLevel'),
                'oldFacilityType' => $request->input('FacilityType'),
                'oldFacilityAgent' => $request->input('FacilityAgent'),
            ];
            return view('pages.polls.reports.contacts_new', $data);
        }
    }
    public function pending(){
        $poll = Poll::find(3);
        $agents = User::where('role_id', 6)->get();
        $subCounties =  SubCounty::where('county_id', '37')->get();
        $type = "";

        if (Gate::allows('is_admin')){
        $users = MalavaParticipant::where('role_id', 4)
        // ->where('caw','MBIRI')
        ->whereNotNull('phone_no')
        ->where('phone_no', '!=', '')
        // ->where('user_type', "participants")
            ->where('call_status', 0)
            // ->where('called_by', Null)
            ->get();
        }else{
            $users = MalavaParticipant::where('role_id', 4)
            // ->where('caw','MBIRI')
            ->whereNotNull('phone_no')
            ->where('phone_no', '!=', '')
            // ->where('user_type', "participants")
            ->where('call_status', 0)
            // ->where('called_by', Null)
            ->get();
            
        }
// dd($users);
        $data = [
            'pagename'=>'All Contacts ',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'users' => $users,
            'poll' => $poll,
            'agents' => $agents,
            'type' => $type,
        ];

        return view('pages.polls.reports.contacts', $data);
    }
    public function declined(){
        $poll = Poll::find(3);
        $agents = User::where('role_id', 6)->get();
        $subCounties =  SubCounty::where('county_id', '37')->get();
        $type = "";

        if (Gate::allows('is_admin')){
        $users = MalavaParticipant::where('role_id', 4)
        // ->where('caw','MBIRI')
        ->whereNotNull('phone_no')
        ->where('phone_no', '!=', '')
        // ->where('user_type', "participants")
        ->where('call_status', 2)
            ->get();
        }else{
            $users = MalavaParticipant::where('role_id', 4)
            // ->where('caw','MBIRI')
            ->whereNotNull('phone_no')
            ->where('phone_no', '!=', '')
            // ->where('user_type', "participants")
            ->where('call_status', 2)
                ->where('called_by', auth()->user()->id)
                ->get();
            
        }

        $data = [
            'pagename'=>'All Contacts ',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'users' => $users,
            'poll' => $poll,
            'agents' => $agents,
            'type' => $type,
        ];

        return view('pages.polls.reports.contacts', $data);
    }
    public function picked(){
        $poll = Poll::find(3);
        $agents = User::where('role_id', 6)->get();
        $subCounties =  SubCounty::where('county_id', '37')->get();
        $type = "";

        if (Gate::allows('is_admin')){
        $users = MalavaParticipant::where('role_id', 4)
        // ->where('caw','MBIRI')
        ->whereNotNull('phone_no')
        ->where('phone_no', '!=', '')
        // ->where('user_type', "participants")
            ->where('call_status', 1)
            ->get();
        }else{
            $users = MalavaParticipant::where('role_id', 4)
            // ->where('caw','MBIRI')
            ->whereNotNull('phone_no')
            ->where('phone_no', '!=', '')
            // ->where('user_type', "participants")
                ->where('call_status', 1)
                ->where('called_by', auth()->user()->id)
                ->get();
            
        }

        $data = [
            'pagename'=>'All Contacts ',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'users' => $users,
            'poll' => $poll,
            'agents' => $agents,
            'type' => $type,
        ];

        return view('pages.polls.reports.contacts', $data);
    }
    public function notreached(){
        $poll = Poll::find(3);
        $agents = User::where('role_id', 6)->get();
        $subCounties =  SubCounty::where('county_id', '37')->get();
        $type = "";

        if (Gate::allows('is_admin')){
        $users = MalavaParticipant::where('role_id', 4)
        // ->where('caw','MBIRI')
        ->whereNotNull('phone_no')
        ->where('phone_no', '!=', '')
        // ->where('user_type', "participants")
            ->where('call_status', 3)
            ->get();
        }else{
            $users = MalavaParticipant::where('role_id', 4)
            // ->where('caw','MBIRI')
            ->whereNotNull('phone_no')
            ->where('phone_no', '!=', '')
            // ->where('user_type', "participants")
             ->where('call_status', 3)
                ->where('called_by', auth()->user()->id)
                ->get();
            
        }

        $data = [
            'pagename'=>'All Contacts ',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'users' => $users,
            'poll' => $poll,
            'agents' => $agents,
            'type' => $type,
        ];

        return view('pages.polls.reports.contacts', $data);
    }

    
    public function index(){
        $users =  User::where('role_id', 6)->get();
        $subCounties =  SubCounty::where('county_id', '37')->get();
        $data = [
            'pagename'=>'Register User',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'users' => $users,
        ];

        return view('pages.users.index', $data);


    }
    public function agents(Request $request){

        if ($request->isMethod('get')) {
            $date1 = Carbon::today()->startOfDay();
            $date2 = Carbon::today()->endOfDay();
        } else if ($request->isMethod('post')) {
            $date1 = Carbon::parse($request->date_1)->startOfDay();
            $date2 = Carbon::parse($request->date_2)->endOfDay();
        }   


        if (Gate::allows('is_admin')){
            $users =  User::where('role_id',  6)->get();
            }else{
                $users =  User::where('role_id',  6)->where('id', auth()->user()->id)->get();
                
            }

        $subCounties =  SubCounty::where('county_id', '37')->get();
        $data = [
            'pagename'=>'Register User',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'date1' => $date1,
            'date2' => $date2,
            'users' => $users
        ];
        return view('pages.polls.reports.agents', $data);


    }
    public function  create()
    {
        $subCounties =  SubCounty::where('county_id', '37')->get();
        $roles = Role::where('id', '!=', 1)->where('id', '!=', 7)->where('id', '!=', 8)->get();
        $data = [
            'pagename'=>'Register User',
            'subCounties' => $subCounties,
            'application_type' => 'admin',
            'roles' => $roles,
        ];

        return view('pages.users.create', $data);
    }
    
    public function store(Request $request){

                // Validate the input data
                    $validator = Validator::make($request->all(), [
                        'first_name' => 'required|string|max:255',
                        'last_name' => 'required|string|max:255',
                        // 'id_no' => 'required|string|max:20|unique:users',
                        'id_no' => 'required',
                        'phone_no' => 'required|string',
                        'email' => 'required|email|max:255|unique:users',
                        'role' => 'required',
                    ]);
        

            // If validation fails, return back with errors and input
            if ($validator->fails()) {
                // dd($request->all(), 'fail', $validator->messages());
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Use DB transactions to save data in a transactional manner
            try {
                DB::beginTransaction();

                // Create a new user instance and populate with input data
                $user = new User();
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->id_no = $request->input('id_no');
                $user->phone_no = $request->input('phone_no');
                $user->email = $request->input('email');
                $user->role_id  = $request->input('role');
                // $user->created_by = Auth::user()->id;
                $user->password = Hash::make($request->input('id_no'));
                

                // Populate other fields as needed

                // Save the user instance to the database
                $user->save();

                // Commit the transaction if all goes well
                DB::commit();
                // dd($request->all(), $user);

                // Redirect to a success page or perform other actions
                // return redirect()->route('/')->with('success', 'User registration successful!');
                return back()->with('success', 'User registration successful!');

            } catch (\Exception $e) {
                DB::rollback();
                dd($request->all(), $e);
                dd($e);
                // Rollback the transaction on error

                // Log the error or perform any other actions as needed
                return back()->with('error', 'An error occurred while registering the user.');
            }
    }
    
    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        dd($request->all(), 'old show');
        return view('pages.users.show', compact('user'));
    }

    public function show(Request $request, User $user)
    {
        // Validation may be added based on your requirements
        // dd($request->all());
        // $user->update($request->all());
        $data = $request->except('password');
        $user->update($data);
        if($request->input('password')){
            $user->password = Hash::make($request->input('password'));
            $user->update();
        }
        return redirect()->route('users.index')->with('success', 'user updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'user deleted successfully');
    }


    public function search(){
        $id_no = request('id_no');
        // <a href="{{ route('your.route.name', ['user_id' => $id_no]) }}">Click me</a>

        $users =  MalavaParticipant::where('id_no', $id_no)->first();
        $subCounties =  SubCounty::where('county_id', '37')->get();

        $data = [
            'pagename'=>'Register User',
            'subCounties' => $subCounties,
            'application_type' => 'member',
            'users' => $users
        ];

        return view('pages.users.register_user', $data);

    }

    public function getSubCountyByCounty($countyId)
    {
        $sub_counties = SubCounty::where('county_id', $countyId)->get();
        return response()->json($sub_counties);
    }

    public function getWardsBySubCounty($subCountyId)
    {
        Log::info('getWardsBySubCounty called', ['subCountyId' => $subCountyId]);

        try {
            $wards = Ward::where('subcounty_id', $subCountyId)->get();
            Log::info('Wards retrieved successfully', [
                'count' => $wards->count(),
                'wards' => $wards->toArray()
            ]);

            return response()->json($wards);
        } catch (\Exception $e) {
            Log::error('Error fetching wards', [
                'subCountyId' => $subCountyId,
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'error' => 'Failed to retrieve wards',
                'message' => $e->getMessage()
            ], 500);
        }
    }
   public function getSubCountyInfo($subCountyId)
    {
        $subCounty = SubCounty::with('county')->find($subCountyId);

        if (!$subCounty) {
            return response()->json(['error' => 'Subcounty not found'], 404);
        }

        return response()->json([
            'subcounty' => $subCounty,
            'county' => $subCounty->county
        ]);
    }



    public function checkExistingData(Request $request)
    {
        $idNoExists = MalavaParticipant::where('id_no', $request->id_no)->exists();
        $phoneNoExists = MalavaParticipant::where('phone_no', $request->phone_no)->exists();
        $emailExists = MalavaParticipant::where('email', $request->useremail)->exists();

        return response()->json([
            'id_no_exists' => $idNoExists,
            'phone_no_exists' => $phoneNoExists,
            'email_exists' => $emailExists,
        ]);
    }
    public function updateCall(Request $request)
    {
        DB::beginTransaction();

        try {
            $participant = MalavaParticipant::findOrFail($request->input('participant_id'));

            Log::info("Call Status Before", [
                'participant_id' => $request->input('participant_id'),
                'call_status' => $participant->call_status,
            ]);

            $participant->call_status = $request->input('call_status');
            $participant->called_by = auth()->id();
            $participant->updated_by = auth()->id();
            $participant->save();

            DB::commit();


            
            Log::info("Call Status After Commit", [
                'participant_id' => $request->input('participant_id'),
                'call_status' => $participant->call_status,
            ]);


            return back()->with('success', 'Poll saved successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error("❌ Failed to update participant call status", [
                'participant_id' => $request->input('participant_id'),
                'user_id' => auth()->id(),
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('participant-answers.addPoll2', $request->input('participant_id'))
                ->with('error', 'Failed to save poll. Please try again.');
        }
    }
    public function assignUser(Request $request)
    {   
        // dd($request->all());
            $participant = MalavaParticipant::find($request->input('participant_id'));
            $participant->called_by = $request->input('updateUserAgent');
            $participant->updated_by = auth()->user()->id;
            $participant->save();
            return back()->with('success', 'Agent Assigned Successfully');
    }
    public function old_update_participant(Request $request)
    {
        try {
            // Validate input
            $validatedData = $request->validate([
                'participant_id' => 'required|exists:users,id',  // Ensure participant exists
                'first_name' => 'required|string|max:255',
                'county' => 'nullable|exists:counties,id',  // Ensure selected county exists
                'sub_county' => 'nullable|exists:sub_counties,id',  // Ensure selected subcounty exists
                'ward' => 'nullable|exists:wards,id',  // Ensure selected ward exists
                'gender' => 'nullable|exists:wards,id',  // Ensure selected ward exists
            ]);
    
            // Find the participant
            $participant = MalavaParticipant::findOrFail($validatedData['participant_id']);
    
            // Update participant details
            $participant->first_name = $validatedData['first_name'];
            $participant->county_id = $validatedData['county'];
            $participant->sub_county_id = $validatedData['sub_county'];
            $participant->ward_id = $validatedData['ward'];
            $participant->updated_by = auth()->user()->id;
    
            // Save the participant data
            $participant->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Mapping Details Saved and updated!'
            ]);
        } catch (\Throwable $e) {
            dd($e);
            // Log the error message
            Log::error('Participant update error: ' . $e->getMessage());
    
            // Redirect back with error message
            // return back()->withErrors(['error' => 'Failed to update mapping details. Please try again.']);

            // return response()->json(['errors' => ['error' => 'Failed to update mapping details. Please try again.']], 422);

    return response()->json([
        'status' => 'error',
        'message' => 'No New Contact Found'
    ], 404);
        }
    }
    

public function update_participant(Request $request)
{
    try {
        // Log the incoming request data
        Log::info('update_participant request received', [
            'request_data' => $request->all(),
            'user_id' => auth()->user()->id ?? 'Guest',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Validate input
        $validatedData = $request->validate([
            'participant_id' => 'required|exists:malava_participants,id', // Changed to users table
            'poll_id' => 'nullable|exists:polls,id',
            'id_number' => 'nullable|string|max:255',
            'gender' => 'nullable|in:M,F',
            'county' => 'nullable|exists:counties,id',
            'sub_county' => 'nullable|exists:sub_counties,id',
            'ward' => 'nullable|exists:wards,id',
            'pstation_code' => 'nullable',
        ]);

        // Log validated data
        Log::info('Validated data for update_participant', [
            'participant_id' => $validatedData['participant_id'],
            'poll_id' => $validatedData['poll_id'] ?? null,
            'id_number' => $validatedData['id_number'] ?? null,
            'gender' => $validatedData['gender'] ?? null,
            'county' => $validatedData['county'] ?? null,
            'sub_county' => $validatedData['sub_county'] ?? null,
            'ward' => $validatedData['ward'] ?? null,
            'pstation_code' => $validatedData['pstation_code'] ?? null,
        ]);

        // Find the participant
        $participant = MalavaParticipant::findOrFail($validatedData['participant_id']);

        // Log current participant data before update
        Log::info('Participant data before update', [
            'participant_id' => $participant->id,
            'current_id_no' => $participant->id_no,
            'current_gender' => $participant->gender,
            'current_county_id' => $participant->county_id,
            'current_sub_county_id' => $participant->sub_county_id,
            'current_ward_id' => $participant->ward_id,
            'pstation_code' => $participant->pstation_code,
        ]);

        // Update participant details
        $participant->id_no = $validatedData['id_number'] ?? $participant->id_no;
        $participant->gender = $validatedData['gender'] ?? $participant->gender;
        $participant->county_id = $validatedData['county'] ?? $participant->county_id;
        $participant->sub_county_id = $validatedData['sub_county'] ?? $participant->sub_county_id;
        $participant->ward_id = $validatedData['ward'] ?? $participant->ward_id;
        $participant->updated_by = auth()->user()->id ?? null; // Handle case where user is not authenticated
        $participant->called_by = auth()->user()->id;
        $participant->call_status = 1;
        $participant->pstation_code = $validatedData['pstation_code'] ?? $participant->pstation_code;

        // Save the participant data
        $participant->save();

        // Log successful update
        Log::info('Participant updated successfully', [
            'participant_id' => $participant->id,
            'updated_id_no' => $participant->id_no,
            'updated_gender' => $participant->gender,
            'updated_county_id' => $participant->county_id,
            'updated_sub_county_id' => $participant->sub_county_id,
            'updated_ward_id' => $participant->ward_id,
            'updated_by' => $participant->updated_by,
            'updated_ward_id' => $participant->ward_id,
            'pstation_code' => $participant->pstation_code,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Mapping Details Saved and updated!'
        ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Log validation errors
        Log::warning('Validation error in update_participant', [
            'errors' => $e->errors(),
            'request_data' => $request->all(),
        ]);

        return response()->json([
            'status' => 'error',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Log model not found error
        Log::error('Participant not found in update_participant', [
            'participant_id' => $request->input('participant_id'),
            'error_message' => $e->getMessage(),
            'stack_trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Participant not found.'
        ], 404);
    } catch (\Throwable $e) {
        // Log general errors
        Log::error('Unexpected error in update_participant', [
            'error_message' => $e->getMessage(),
            'stack_trace' => $e->getTraceAsString(),
            'request_data' => $request->all(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update mapping details. Please try again.'
        ], 500);
    }
}




        
}
