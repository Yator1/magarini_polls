<?php

namespace App\Http\Controllers;

use App\Models\Aggregator;
use App\Models\MangoProduction;
use App\Models\ParticipantAnswer;
use App\Models\PollingStation;
use App\Models\MalavaParticipant;
use App\Models\Poll;
use App\Models\Mobilizer;
use App\Models\PollAnswer;
use App\Models\PollQuestion;
use App\Models\SubCounty;
use App\Models\Tender;
use App\Models\TenderApplication;
use App\Models\User;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   public function __construct()
{
    $this->middleware('auth')->except(['adminDashboard2']);
}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $themePath = 'theme.' . $request->path();

        if (view()->exists($themePath)) {
            return view($themePath);
        }
        return abort(404);
    }

    public function root()
    {

        //redirect users to respective dashboards

        if (Gate::allows('is_admin')){

            return redirect('admin-dashboard');

        } elseif (Gate::allows('is_staff')){

            return redirect('admin-dashboard');
        } elseif (Gate::allows('is_agent')){

            return redirect('admin-dashboard');

        }else{
            
            return abort(404);
        }


        // return view('theme.index');
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar =  $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "User Details Updated successfully!"
            // ], 200); // Status code here
            return redirect()->back();
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "Something went wrong!"
            // ], 200); // Status code here
            return redirect()->back();

        }
    }

    public function updatePassword(Request $request, $id)
        {
            $request->validate([
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
                return response()->json([
                    'isSuccess' => false,
                    'Message' => "Your Current password does not matches with the password you provided. Please try again."
                ], 200); // Status code
            } else {
                $user = User::find($id);
                $user->password = Hash::make($request->get('password'));
                $user->update();
                if ($user) {
                    Session::flash('message', 'Password updated successfully!');
                    Session::flash('alert-class', 'alert-success');
                    return response()->json([
                        'isSuccess' => true,
                        'Message' => "Password updated successfully!"
                    ], 200); // Status code here
                } else {
                    Session::flash('message', 'Something went wrong!');
                    Session::flash('alert-class', 'alert-danger');
                    return response()->json([
                        'isSuccess' => true,
                        'Message' => "Something went wrong!"
                    ], 200); // Status code here
                }
            }
        }
    public function adminDashboard(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '400');

        $user = User::find(Auth::user()->id);
        $isAdmin = in_array($user->role_id, [1, 2]);

        // Fetch data, filtered for Mbeere North (subcounty_id 201)
        $subcounties = SubCounty::where('county_id', 3)->where('id', 17)->get();
        $wards = Ward::where('county_id', 3)->where('subcounty_id', 17)->get();
        $pollingStations = PollingStation::get();

        // Date range
        if ($request->isMethod('get')) {
            $defaultDate = Cache::remember('earliest_participant_date', now()->addHours(24), function () {
                        return MalavaParticipant::
                        // where('role_id', 4)
                            // ->where('user_type', 'participants')
                            whereNotNull('phone_no')
                            ->where('phone_no', '!=', '')
                            ->where('sub_county_id', 201)
                            ->min('updated_at') ?? Carbon::today()->startOfDay();
                    });
            $date1 = Carbon::parse($defaultDate)->startOfDay();
            $date2 = Carbon::today()->endOfDay();
        } else {
            $date1 = Carbon::parse($request->date_1)->startOfDay();
            $date2 = Carbon::parse($request->date_2)->endOfDay();
        }

        // Base query for participants
        $participantsQuery = MalavaParticipant::query()
            // ->where('role_id', 4)
            // ->where('user_type', 'participants')
            ->whereNotNull('phone_no')
            ->where('phone_no', '!=', '')
            ->where('sub_county_id', 201)
            ->whereBetween('updated_at', [$date1, $date2]);

        if (!$isAdmin) {
            $participantsQuery->where('called_by', $user->id);
        }

        // Call statistics
        $callStats = [
            'declined_unreachable' => (clone $participantsQuery)->where('call_status', 2)->count(),
            'missed' => (clone $participantsQuery)->where('call_status', 3)->count(),
            'calls_back' => (clone $participantsQuery)->where('call_status', 7)->count(),
            'invalid_phone' => (clone $participantsQuery)->where('call_status', 8)->count(),
            'picked_participated' => (clone $participantsQuery)->where('call_status', 1)->count(),
            'not_contacted' => MalavaParticipant::
            // where('role_id', 4)
                // ->where('user_type', 'participants')
                whereNull('called_by')
                ->where('call_status', 0)
                ->whereNotNull('phone_no')
                ->where('phone_no', '!=', '')
                ->where('sub_county_id', 201)
                ->count(),
        ];

        if (!$isAdmin) {
            $callStats['picked_participated'] = $user->calls()
                ->where('call_status', 1)
                // ->where('user_type', 'participants')
                ->whereBetween('updated_at', [$date1, $date2])
                // ->whereHas('participantAnswers')
                ->count();
        }

        // Percentage calculations
        $totalCalled = array_sum(array_slice($callStats, 0, 5)); // Sum of contacted statuses
        $totalUsers = $totalCalled + $callStats['not_contacted'];
        $percentages = [];
        $normalizationBase = 1000; // As per your requirement
        foreach ($callStats as $key => $val) {
            if ($key === 'not_contacted') {
                $percentages[$key] = $totalUsers ? round(($val / $totalUsers) * 100, 2) : 0;
            } else {
                $percentages[$key] = $totalCalled ? round(($val / $totalCalled) * $normalizationBase / 10, 2) : 0;
            }
        }

        // Participating users
        $participatingQuery = MalavaParticipant::query()
            // ->where('role_id', 4)
            // ->where('user_type', 'participants')
            ->where('call_status', 1)
            ->whereBetween('updated_at', [$date1, $date2])
            ->whereHas('participantAnswers')
            ->where('sub_county_id', 201);

        if (!$isAdmin) {
            $participatingQuery->where('called_by', $user->id);
        }

        $participatingIds = $participatingQuery->pluck('id');
        $totalParticipants = $participatingIds->count();


        // All users who have been contacted
        $allcontactedUsers = MalavaParticipant::query()
            // ->where('role_id', 4)
            // ->where('user_type', 'participants')
            ->where('call_status', '!=', 0)
            ->whereBetween('updated_at', [$date1, $date2])
            ->where('sub_county_id', 201);

        if (!$isAdmin) {
            $allcontactedUsers->where('called_by', $user->id);
        }

        $allcontactedUsersIds = $allcontactedUsers->pluck('id');
        $totalAllcontactedUsers = $allcontactedUsersIds->count();

        // Group totals
        $subcountyTotals = $participatingQuery->groupBy('sub_county_id')
            ->select('sub_county_id', DB::raw('count(*) as total'))
            ->pluck('total', 'sub_county_id');
        $wardTotals = $participatingQuery->groupBy('ward_id')
            ->select('ward_id', DB::raw('count(*) as total'))
            ->pluck('total', 'ward_id');
        $pollingTotals = $participatingQuery->groupBy('pstation_code')
            ->select('pstation_code', DB::raw('count(*) as total'))
            ->pluck('total', 'pstation_code');

        // Sort collections by totals
        $subcounties = $subcounties->sortByDesc(function ($sc) use ($subcountyTotals) {
            return $subcountyTotals[$sc->id] ?? 0;
        })->keyBy('id');

        $wards = $wards->sortByDesc(function ($w) use ($wardTotals) {
            return $wardTotals[$w->id] ?? 0;
        });

        $pollingStations = $pollingStations->sortByDesc(function ($ps) use ($pollingTotals) {
            return $pollingTotals[$ps->id] ?? 0;
        });

        // Poll data
        $polls = Poll::where('id', '!=', 1)->get();
        $poll = Poll::with('questions.answers')->find(3);
        $questions = $poll ? $poll->questions : collect();

        $pollData = [];
        $perSubcountyData = [];
        $perWardData = [];
        $perPollingData = [];

        foreach ($questions as $question) {
            $qid = $question->id;
            $answersQuery = ParticipantAnswer::whereIn('participant_id', $participatingIds)
                ->where('poll_question_id', $qid);

            // Overall
            if ($question->question_type === 'multiple') {
                $answerCounts = $answersQuery->groupBy('answer_id')
                    ->select('answer_id', DB::raw('count(*) as count'))
                    ->pluck('count', 'answer_id');
                $blankCount = $answerCounts[null] ?? 0;
                unset($answerCounts[null]);

                $data = [
                    'id' => $qid,
                    'question' => $question->question,
                    'question_type' => $question->question_type,
                    'answer_type' => $question->answer_type,
                    'total_participants' => $totalParticipants,
                    'blanks' => $blankCount,
                    'answers' => [],
                ];

                $answers = $question->answers->map(function ($ans) use ($answerCounts, $totalParticipants) {
                    $count = $answerCounts[$ans->id] ?? 0;
                    $perc = $totalParticipants ? round($count / $totalParticipants * 100, 2) : 0;
                    return ['id' => $ans->id, 'answer' => $ans->answer, 'count' => $count, 'percentage' => $perc];
                })->sortByDesc('count')->values();

                $data['answers'] = $answers->toArray();
            } else {
                $data = [
                    'id' => $qid,
                    'question' => $question->question,
                    'question_type' => $question->question_type,
                    'answer_type' => $question->answer_type,
                    'total_participants' => $totalParticipants,
                    'blanks' => $answersQuery->whereNull('answer')->count(),
                    'answers' => $question->answer_type === 'text'
                        ? $answersQuery->whereNotNull('answer')->count()
                        : $answersQuery->whereNotNull('answer')->sum(DB::raw('CAST(answer AS DECIMAL)')),
                ];
            }
            $pollData[$qid] = $data;

            // Per level data
            if ($question->question_type === 'multiple') {
                $subQuery = ParticipantAnswer::join('malava_participants', 'participants_answers.participant_id', '=', 'malava_participants.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('malava_participants.sub_county_id', 'answer_id')
                    ->select('malava_participants.sub_county_id', 'answer_id', DB::raw('count(*) as count'))
                    ->get();
                $perSubcountyData[$qid] = $subQuery->groupBy('sub_county_id')->map(function ($group) {
                    return $group->pluck('count', 'answer_id');
                });

                $wardQuery = ParticipantAnswer::join('malava_participants', 'participants_answers.participant_id', '=', 'malava_participants.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('malava_participants.ward_id', 'answer_id')
                    ->select('malava_participants.ward_id', 'answer_id', DB::raw('count(*) as count'))
                    ->get();
                $perWardData[$qid] = $wardQuery->groupBy('ward_id')->map(function ($group) {
                    return $group->pluck('count', 'answer_id');
                });

                $pollingQuery = ParticipantAnswer::join('malava_participants', 'participants_answers.participant_id', '=', 'malava_participants.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('malava_participants.pstation_code', 'answer_id')
                    ->select('malava_participants.pstation_code', 'answer_id', DB::raw('count(*) as count'))
                    ->get();
                $perPollingData[$qid] = $pollingQuery->groupBy('pstation_code')->map(function ($group) {
                    return $group->pluck('count', 'answer_id');
                });
            } else {
                $valueExpr = $question->answer_type === 'text'
                    ? 'COUNT(CASE WHEN participant_answers.answer IS NOT NULL THEN 1 END) as value'
                    : 'SUM(CAST(participant_answers.answer AS DECIMAL)) as value';

                $subQuery = ParticipantAnswer::join('malava_participants', 'participants_answers.participant_id', '=', 'malava_participants.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('malava_participants.sub_county_id')
                    ->select('malava_participants.sub_county_id', DB::raw($valueExpr), DB::raw('COUNT(CASE WHEN participant_answers.answer IS NULL THEN 1 END) as blanks'))
                    ->get();
                $perSubcountyData[$qid] = $subQuery->keyBy('sub_county_id')->map(function ($row) {
                    return ['value' => $row->value, 'blanks' => $row->blanks];
                });

                $wardQuery = ParticipantAnswer::join('malava_participants', 'participants_answers.participant_id', '=', 'malava_participants.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('malava_participants.ward_id')
                    ->select('malava_participants.ward_id', DB::raw($valueExpr), DB::raw('COUNT(CASE WHEN participant_answers.answer IS NULL THEN 1 END) as blanks'))
                    ->get();
                $perWardData[$qid] = $wardQuery->keyBy('ward_id')->map(function ($row) {
                    return ['value' => $row->value, 'blanks' => $row->blanks];
                });

                $pollingQuery = ParticipantAnswer::join('malava_participants', 'participants_answers.participant_id', '=', 'malava_participants.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('malava_participants.pstation_code')
                    ->select('malava_participants.pstation_code', DB::raw($valueExpr), DB::raw('COUNT(CASE WHEN participant_answers.answer IS NULL THEN 1 END) as blanks'))
                    ->get();
                $perPollingData[$qid] = $pollingQuery->keyBy('pstation_code')->map(function ($row) {
                    return ['value' => $row->value, 'blanks' => $row->blanks];
                });
            }
        }

        // Agent stats
        $agents = User::where('role_id', 6)->get();
        $agentStats = [];
        if ($isAdmin) {
            foreach ($agents as $agent) {
                $agentQuery = MalavaParticipant::where('called_by', $agent->id);
                    // ->where('role_id', 4)
                    // ->where('user_type', 'participants')
                    // ->whereBetween('updated_at', [$date1, $date2]);
                $agentStats[$agent->id] = [
                    'name' => $agent->first_name . ' ' . $agent->last_name,
                    'declined_unreachable' => (clone $agentQuery)->where('call_status', 2)->count(),
                    'missed' => (clone $agentQuery)->where('call_status', 3)->count(),
                    'calls_back' => (clone $agentQuery)->where('call_status', 7)->count(),
                    'invalid_phone' => (clone $agentQuery)->where('call_status', 8)->count(),
                    'picked_participated' => (clone $agentQuery)->where('call_status', 1)->count(),
                ];
            }
            $agentStats = collect($agentStats)->sortByDesc('picked_participated')->toArray();
        }

        $data = [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'subcounties' => $subcounties,
            'wards' => $wards,
            'pollingStations' => $pollingStations,
            'callStats' => $callStats,
            'percentages' => $percentages,
            'date1' => $date1,
            'date2' => $date2,
            'polls' => $polls,
            'poll' => $poll,
            'pollData' => $pollData,
            'perSubcountyData' => $perSubcountyData,
            'perWardData' => $perWardData,
            'perPollingData' => $perPollingData,
            'subcountyTotals' => $subcountyTotals,
            'wardTotals' => $wardTotals,
            'pollingTotals' => $pollingTotals,
            'totalParticipants' => $totalParticipants,
            'totalCalled' => $totalCalled,
            'totalUsers' => $totalUsers,
            'allcontactedUsers' => $allcontactedUsers,
            'totalAllcontactedUsers' => $totalAllcontactedUsers,
            'agentStats' => $agentStats,
        ];

        return view('pages.dashboards.admin', $data);
    }
    public function adminDashboard2(Request $request)
    {
        // dd('yaay');
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '400');

        $user = User::find(1);
        $isAdmin = in_array($user->role_id, [1, 2]);

        // Fetch data, filtered for Mbeere North (subcounty_id 201)
        $subcounties = SubCounty::where('county_id', 37)->where('id', 201)->get();
        $wards = Ward::where('county_id', 37)->where('subcounty_id', 201)->get();
        $pollingStations = PollingStation::get();

        // Date range
        if ($request->isMethod('get')) {
            $defaultDate = Cache::remember('earliest_participant_date', now()->addHours(24), function () {
                        return MalavaParticipant::where('role_id', 4)
                            // ->where('user_type', 'participants')
                            ->whereNotNull('phone_no')
                            ->where('phone_no', '!=', '')
                            ->where('sub_county_id', 201)
                            ->min('updated_at') ?? Carbon::today()->startOfDay();
                    });
            $date1 = Carbon::parse($defaultDate)->startOfDay();
            $date2 = Carbon::today()->endOfDay();
        } else {
            $date1 = Carbon::parse($request->date_1)->startOfDay();
            $date2 = Carbon::parse($request->date_2)->endOfDay();
        }

        // Base query for participants
        $participantsQuery = MalavaParticipant::query()
            ->where('role_id', 4)
            // ->where('user_type', 'participants')
            ->whereNotNull('phone_no')
            ->where('phone_no', '!=', '')
            ->where('sub_county_id', 201)
            ->whereBetween('updated_at', [$date1, $date2]);

        if (!$isAdmin) {
            $participantsQuery->where('called_by', $user->id);
        }

        // Call statistics
        $callStats = [
            'declined_unreachable' => (clone $participantsQuery)->where('call_status', 2)->count(),
            'missed' => (clone $participantsQuery)->where('call_status', 3)->count(),
            'calls_back' => (clone $participantsQuery)->where('call_status', 7)->count(),
            'invalid_phone' => (clone $participantsQuery)->where('call_status', 8)->count(),
            'picked_participated' => (clone $participantsQuery)->where('call_status', 1)->count(),
            'not_contacted' => MalavaParticipant::where('role_id', 4)
                // ->where('user_type', 'participants')
                ->whereNull('called_by')
                ->where('call_status', 0)
                ->whereNotNull('phone_no')
                ->where('phone_no', '!=', '')
                ->where('sub_county_id', 201)
                ->count(),
        ];

        if (!$isAdmin) {
            $callStats['picked_participated'] = $user->calls()
                ->where('call_status', 1)
                // ->where('user_type', 'participants')
                ->whereBetween('updated_at', [$date1, $date2])
                // ->whereHas('participantAnswers')
                ->count();
        }

        // Percentage calculations
        $totalCalled = array_sum(array_slice($callStats, 0, 5)); // Sum of contacted statuses
        $totalUsers = $totalCalled + $callStats['not_contacted'];
        $percentages = [];
        $normalizationBase = 1000; // As per your requirement
        foreach ($callStats as $key => $val) {
            if ($key === 'not_contacted') {
                $percentages[$key] = $totalUsers ? round(($val / $totalUsers) * 100, 2) : 0;
            } else {
                $percentages[$key] = $totalCalled ? round(($val / $totalCalled) * $normalizationBase / 10, 2) : 0;
            }
        }

        // Participating users
        $participatingQuery = MalavaParticipant::query()
            ->where('role_id', 4)
            // ->where('user_type', 'participants')
            ->where('call_status', 1)
            ->whereBetween('updated_at', [$date1, $date2])
            ->whereHas('participantAnswers')
            ->where('sub_county_id', 201);

        if (!$isAdmin) {
            $participatingQuery->where('called_by', $user->id);
        }

        $participatingIds = $participatingQuery->pluck('id');
        $totalParticipants = $participatingIds->count();


        // All users who have been contacted
        $allcontactedUsers = MalavaParticipant::query()
            ->where('role_id', 4)
            // ->where('user_type', 'participants')
            ->where('call_status', '!=', 0)
            ->whereBetween('updated_at', [$date1, $date2])
            ->where('sub_county_id', 201);

        if (!$isAdmin) {
            $allcontactedUsers->where('called_by', $user->id);
        }

        $allcontactedUsersIds = $allcontactedUsers->pluck('id');
        $totalAllcontactedUsers = $allcontactedUsersIds->count();

        // Group totals
        $subcountyTotals = $participatingQuery->groupBy('sub_county_id')
            ->select('sub_county_id', DB::raw('count(*) as total'))
            ->pluck('total', 'sub_county_id');
        $wardTotals = $participatingQuery->groupBy('ward_id')
            ->select('ward_id', DB::raw('count(*) as total'))
            ->pluck('total', 'ward_id');
        $pollingTotals = $participatingQuery->groupBy('pstation_code')
            ->select('pstation_code', DB::raw('count(*) as total'))
            ->pluck('total', 'pstation_code');

        // Sort collections by totals
        $subcounties = $subcounties->sortByDesc(function ($sc) use ($subcountyTotals) {
            return $subcountyTotals[$sc->id] ?? 0;
        })->keyBy('id');

        $wards = $wards->sortByDesc(function ($w) use ($wardTotals) {
            return $wardTotals[$w->id] ?? 0;
        });

        $pollingStations = $pollingStations->sortByDesc(function ($ps) use ($pollingTotals) {
            return $pollingTotals[$ps->id] ?? 0;
        });

        // Poll data
        $polls = Poll::where('id', '!=', 1)->get();
        $poll = Poll::with('questions.answers')->find(3);
        $questions = $poll ? $poll->questions : collect();

        $pollData = [];
        $perSubcountyData = [];
        $perWardData = [];
        $perPollingData = [];

        foreach ($questions as $question) {
            $qid = $question->id;
            $answersQuery = ParticipantAnswer::whereIn('participant_id', $participatingIds)
                ->where('poll_question_id', $qid);

            // Overall
            if ($question->question_type === 'multiple') {
                $answerCounts = $answersQuery->groupBy('answer_id')
                    ->select('answer_id', DB::raw('count(*) as count'))
                    ->pluck('count', 'answer_id');
                $blankCount = $answerCounts[null] ?? 0;
                unset($answerCounts[null]);

                $data = [
                    'id' => $qid,
                    'question' => $question->question,
                    'question_type' => $question->question_type,
                    'answer_type' => $question->answer_type,
                    'total_participants' => $totalParticipants,
                    'blanks' => $blankCount,
                    'answers' => [],
                ];

                $answers = $question->answers->map(function ($ans) use ($answerCounts, $totalParticipants) {
                    $count = $answerCounts[$ans->id] ?? 0;
                    $perc = $totalParticipants ? round($count / $totalParticipants * 100, 2) : 0;
                    return ['id' => $ans->id, 'answer' => $ans->answer, 'count' => $count, 'percentage' => $perc];
                })->sortByDesc('count')->values();

                $data['answers'] = $answers->toArray();
            } else {
                $data = [
                    'id' => $qid,
                    'question' => $question->question,
                    'question_type' => $question->question_type,
                    'answer_type' => $question->answer_type,
                    'total_participants' => $totalParticipants,
                    'blanks' => $answersQuery->whereNull('answer')->count(),
                    'answers' => $question->answer_type === 'text'
                        ? $answersQuery->whereNotNull('answer')->count()
                        : $answersQuery->whereNotNull('answer')->sum(DB::raw('CAST(answer AS DECIMAL)')),
                ];
            }
            $pollData[$qid] = $data;

            // Per level data
            if ($question->question_type === 'multiple') {
                $subQuery = ParticipantAnswer::join('users', 'participants_answers.participant_id', '=', 'users.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('users.sub_county_id', 'answer_id')
                    ->select('users.sub_county_id', 'answer_id', DB::raw('count(*) as count'))
                    ->get();
                $perSubcountyData[$qid] = $subQuery->groupBy('sub_county_id')->map(function ($group) {
                    return $group->pluck('count', 'answer_id');
                });

                $wardQuery = ParticipantAnswer::join('users', 'participants_answers.participant_id', '=', 'users.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('users.ward_id', 'answer_id')
                    ->select('users.ward_id', 'answer_id', DB::raw('count(*) as count'))
                    ->get();
                $perWardData[$qid] = $wardQuery->groupBy('ward_id')->map(function ($group) {
                    return $group->pluck('count', 'answer_id');
                });

                $pollingQuery = ParticipantAnswer::join('users', 'participants_answers.participant_id', '=', 'users.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('users.pstation_code', 'answer_id')
                    ->select('users.pstation_code', 'answer_id', DB::raw('count(*) as count'))
                    ->get();
                $perPollingData[$qid] = $pollingQuery->groupBy('pstation_code')->map(function ($group) {
                    return $group->pluck('count', 'answer_id');
                });
            } else {
                $valueExpr = $question->answer_type === 'text'
                    ? 'COUNT(CASE WHEN participant_answers.answer IS NOT NULL THEN 1 END) as value'
                    : 'SUM(CAST(participant_answers.answer AS DECIMAL)) as value';

                $subQuery = ParticipantAnswer::join('users', 'participants_answers.participant_id', '=', 'users.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('users.sub_county_id')
                    ->select('users.sub_county_id', DB::raw($valueExpr), DB::raw('COUNT(CASE WHEN participant_answers.answer IS NULL THEN 1 END) as blanks'))
                    ->get();
                $perSubcountyData[$qid] = $subQuery->keyBy('sub_county_id')->map(function ($row) {
                    return ['value' => $row->value, 'blanks' => $row->blanks];
                });

                $wardQuery = ParticipantAnswer::join('users', 'participants_answers.participant_id', '=', 'users.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('users.ward_id')
                    ->select('users.ward_id', DB::raw($valueExpr), DB::raw('COUNT(CASE WHEN participant_answers.answer IS NULL THEN 1 END) as blanks'))
                    ->get();
                $perWardData[$qid] = $wardQuery->keyBy('ward_id')->map(function ($row) {
                    return ['value' => $row->value, 'blanks' => $row->blanks];
                });

                $pollingQuery = ParticipantAnswer::join('users', 'participants_answers.participant_id', '=', 'users.id')
                    ->where('poll_question_id', $qid)
                    ->whereIn('participants_answers.participant_id', $participatingIds)
                    ->groupBy('users.pstation_code')
                    ->select('users.pstation_code', DB::raw($valueExpr), DB::raw('COUNT(CASE WHEN participant_answers.answer IS NULL THEN 1 END) as blanks'))
                    ->get();
                $perPollingData[$qid] = $pollingQuery->keyBy('pstation_code')->map(function ($row) {
                    return ['value' => $row->value, 'blanks' => $row->blanks];
                });
            }
        }

        // Agent stats
        $agents = User::where('role_id', 6)->get();
        $agentStats = [];
        if ($isAdmin) {
            foreach ($agents as $agent) {
                $agentQuery = User::where('called_by', $agent->id)
                    ->where('role_id', 4)
                    // ->where('user_type', 'participants')
                    ->whereBetween('updated_at', [$date1, $date2]);
                $agentStats[$agent->id] = [
                    'name' => $agent->first_name . ' ' . $agent->last_name,
                    'declined_unreachable' => (clone $agentQuery)->where('call_status', 2)->count(),
                    'missed' => (clone $agentQuery)->where('call_status', 3)->count(),
                    'calls_back' => (clone $agentQuery)->where('call_status', 7)->count(),
                    'invalid_phone' => (clone $agentQuery)->where('call_status', 8)->count(),
                    'picked_participated' => (clone $agentQuery)->where('call_status', 1)->count(),
                ];
            }
            $agentStats = collect($agentStats)->sortByDesc('picked_participated')->toArray();
        }

        return view('pages.dashboards.admin2', compact(
            'user', 'subcounties', 'wards', 'pollingStations','totalParticipants',
            'callStats', 'percentages', 'date1', 'date2', 'polls', 'poll',
            'pollData', 'perSubcountyData', 'perWardData', 'perPollingData',
            'subcountyTotals', 'wardTotals', 'pollingTotals','allcontactedUsers','totalAllcontactedUsers',
            'agentStats', 'isAdmin', 'totalCalled', 'totalUsers'
        ));
    }
    public function OldadminDashboard(Request $request){

            ini_set('memory_limit', '512M'); // Increase memory limit to 256MB
        ini_set('max_execution_time', '400'); // Increase execution time to 300 seconds

        $user =  User::find(1);

        $subcounties = SubCounty::where('county_id', 21)->get();
        $wards = Ward::where('county_id', 21)->get();


        if ($request->isMethod('get')) {
            $date1 = Carbon::today()->startOfDay();
            $date2 = Carbon::today()->endOfDay();
        } else if ($request->isMethod('post')) {
            $date1 = Carbon::parse($request->date_1)->startOfDay();
            $date2 = Carbon::parse($request->date_2)->endOfDay();
        }        


        $usersQueryMain = User::query()->select('call_status', 'role_id', 'phone_no', 'updated_at','called_by' )
                                        ->where('role_id', 4)
                                        ->whereNotNull('phone_no')
                                        ->where('phone_no', '!=', '')
                                        ->whereBetween('updated_at', [$date1, $date2]);

        $usersQueryMainUnfiltered = User::where('role_id', 4)
                                        ->where('called_by', Null)
                                        ->where('call_status', 0)
                                        ->where('user_type', "participants")
                                        ->count();

            if($user->role_id != 1 && $user->role_id != 2){
                // Apply additional condition if the user is not role_id 1 or 2
                $usersQueryMain->where('called_by', $user->id);
            }

            // Now, you can reuse this query for different conditions without repeating the same query multiple times:
            $usersQuery = clone $usersQueryMain;
            $usersQuery2 = clone $usersQueryMain;
            $usersQuery3 = clone $usersQueryMain;
            $usersQuery4 = clone $usersQueryMain;
            $usersQuery5 = clone $usersQueryMain;
            $usersQuery6 = $usersQueryMainUnfiltered;
            $usersQuery7 = clone $usersQueryMain;
            $usersQuery8 = clone $usersQueryMain;
            $usersQuery9 = clone $usersQueryMain;


        $polls = Poll::where('id', '!=', 1)->get();
        $poll = Poll::where('id',3)->first();
        $poll_questions = $poll->questions;

            $participants = User::with([
                'participantAnswers' => function ($query) use ($poll_questions) {
                    $query->whereIn('poll_question_id', $poll_questions->pluck('id'));
                }
            ])
            ->where('call_status', 1)
            // ->where('user_type', 'participants')
            ->whereBetween('updated_at', [$date1, $date2])
            ->get();
        
        



        $pollData = $poll_questions->map(function ($poll_question) use ($participants) {
            $data = [
                'id' => $poll_question->id,
                'question_type' => $poll_question->question_type,
                'answers' => [],
                'blanks' => 0,
                'total_participants' => $participants->count(),
            ];
        
            if ($poll_question->question_type === 'multiple') {
                foreach ($poll_question->answers as $poll_answer) {
                    $count = $participants->filter(function ($participant) use ($poll_question, $poll_answer) {
                        return $participant->participantAnswers->where('poll_question_id', $poll_question->id)
                            ->where('answer_id', $poll_answer->id)
                            ->isNotEmpty();
                    })->count();
        
                    $data['answers'][] = [
                        'id' => $poll_answer->id,
                        'count' => $count,
                        'percentage' => $data['total_participants'] > 0 
                            ? round(($count / $data['total_participants']) * 100, 2)
                            : 0,
                    ];
                }
        
                $data['blanks'] = $participants->filter(function ($participant) use ($poll_question) {
                    return $participant->participantAnswers->where('poll_question_id', $poll_question->id)
                        ->whereNull('answer_id')
                        ->isNotEmpty();
                })->count();
            } else {
                $data['answers'] = $participants->sum(function ($participant) use ($poll_question) {
                    // Check if the answer type is 'text'
                    if ($poll_question->answer_type == 'text') {
                        // Count the number of answers (instead of summing them)
                        return $participant->participantAnswers->where('poll_question_id', $poll_question->id)->count();
                    } else {
                        // Sum the numeric answers
                        return $participant->participantAnswers->where('poll_question_id', $poll_question->id)
                            ->sum(function ($answer) {
                                return is_numeric($answer->answer) ? (float) $answer->answer : 0;
                            });
                    }
                });
                
                
        
                $data['blanks'] = $participants->filter(function ($participant) use ($poll_question) {
                    return $participant->participantAnswers->where('poll_question_id', $poll_question->id)
                        ->whereNull('answer')
                        ->isNotEmpty();
                })->count();
            }
        
            return $data;
        });
        return view('pages.dashboards.admin', [
                    'user' => $user,
                    'subcounties' => $subcounties,
                    'usersQuery' => $usersQuery,
                    'pollData' => $pollData,
                    'usersQuery2' => $usersQuery2,
                    'usersQuery3' => $usersQuery3,
                    'usersQuery4' => $usersQuery4,
                    'usersQuery5' => $usersQuery5,
                    'usersQuery6' => $usersQuery6,
                    'usersQuery7' => $usersQuery7,
                    'usersQuery8' => $usersQuery8,
                    'usersQuery9' => $usersQuery9,
                    'date1' => $date1,
                    'date2' => $date2,
                    'wards' => $wards,
                    'polls' => $polls,

                ]);

    }

    public function storeDashboard(){

        if (Gate::allows('admins')) {

        } elseif(Gate::allows('other_staff')) {

        } elseif(Gate::allows('is_storekeeper')) {

        }  else {
            return back();
        }
        return view('pages.dashboards.store');
    }
}
