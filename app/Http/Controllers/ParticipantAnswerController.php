<?php

namespace App\Http\Controllers;

use App\Models\ParticipantAnswer;
use App\Models\Poll;
use App\Models\PollAnswer;
use Illuminate\Support\Facades\Validator;
use App\Models\PollQuestion;
use App\Models\PollingStation;
use App\Models\User;
use App\Models\MalavaParticipant;
use App\Models\Mobilizer;
use App\Models\Counties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\select;

class ParticipantAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    try {
        $poll = Poll::find(3);
        if (!$poll) {
            return back()->with('error', 'Poll not found.');
        }

        // Preload data (no heavy joins)
        $poll_questions = PollQuestion::where('poll_id', $poll->id)->get();
        $question_ids = $poll_questions->pluck('id')->toArray();

        $agents = User::whereIn('role_id', [6, 1])->get();
        $counties = Counties::where('id', 37)->get();

        $poll_answers = PollAnswer::where('poll_id', $poll->id)
            ->whereIn('poll_question_id', $question_ids)
            ->get();

        $participant_answers = ParticipantAnswer::where('poll_id', $poll->id)->get();
        $participant_ids = $participant_answers->pluck('participant_id')->toArray();

         // ✅ Check if this agent already has an assigned participant not completed
        $existingUser = MalavaParticipant::where('called_by', auth()->id())
            ->where('call_status', 0) // 1 = assigned but not yet updated
            ->get();
            // dd($existingUser, auth()->id());

        if ($existingUser) {
            $user = $existingUser;

            $poll = Poll::find(3);
            $poll_questions = PollQuestion::where('poll_id', $poll->id)->get();
            $question_ids = $poll_questions->pluck('id')->toArray();
            $poll_answers = PollAnswer::where('poll_id', $poll->id)
                                ->whereIn('poll_question_id', $question_ids)
                                ->get();
            $participant_answers = ParticipantAnswer::where('poll_id', $poll->id)->get();
            $participant_ids = $participant_answers->pluck('participant_id')->toArray();

            $agents = User::whereIn('role_id', [6, 1])->get();
            $counties = Counties::where('id', 37)->get();
            $pollingStations = PollingStation::all();

            // ✅ Return the same participant, don't fetch new!
            return view('pages.polls.participant_answer.create', compact(
                'poll',
                'agents',
                'pollingStations',
                'counties',
                'poll_questions',
                'poll_answers',
                'user',
                'participant_answers'
            ));
        }

         
        DB::beginTransaction();

        try {
            $user = MalavaParticipant::whereNotNull('phone_no')
                ->where('phone_no', '!=', '')
                ->where('sub_county_id', 201)
                ->whereNotIn('id', $participant_ids)
                ->where(function ($q) {
                    $q->where(function ($q2) {
                        $q2->where('call_status', 0)
                            ->where('call_2_status', 0)
                            ->whereNull('called_by');
                    })->orWhere('called_by', auth()->id());
                })
                // ->orderBy('id')
            ->inRandomOrder()
                ->lockForUpdate()
                ->first();

            if (!$user) {
                DB::rollBack();
                return back()->with('error', 'No new participants available. Try again later.');
            }

            // Atomic update check
            $updated = MalavaParticipant::where('id', $user->id)
                ->where(function ($q) {
                    $q->whereNull('called_by')
                    ->orWhere('called_by', auth()->id());
                })
                ->update([
                    'called_by'   => auth()->id(),
                    'call_status' => 1,
                    'updated_by'  => auth()->id(),
                ]);

            if (!$updated) {
                DB::rollBack();
                return back()->with('error', 'Participant just got assigned to another agent. Please try again.');
            }

            DB::commit();

            Log::info('Participant assigned successfully', [
                'participant_id' => $user->id,
                'agent_id' => auth()->id(),
                'poll_id' => 3,
            ]);


        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Assignment error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Something went wrong. Try again.');
        }


        $pollingStations = PollingStation::all();

        return view('pages.polls.participant_answer.create', compact(
            'poll',
            'agents',
            'pollingStations',
            'counties',
            'poll_questions',
            'poll_answers',
            'user',
            'participant_answers'
        ));
    } catch (\Exception $e) {
        Log::error('Error assigning participant for poll', [
            'error' => $e->getMessage(),
            'poll_id' => 3,
            'agent_id' => auth()->user()->id,
        ]);
        return back()->with('error', 'An error occurred while assigning a participant. Please try again.');
    }
}


    
    // 7904581
    
    public function addPoll2($id)
    {
        $poll = Poll::find(3);
        $poll_questions = PollQuestion::where('poll_id', $poll->id)->get();
        $question_ids = $poll_questions->pluck('id')->toArray();
        $agents = User::where('role_id', 6)->get();
        $counties =  Counties::all();
       
       

          // Using distinct to optimize queries and keep variable naming consistent
        //   $FacilityLevels = Cache::remember('facilityLevels', 60, function() {
        //     return MalavaParticipant::distinct()->pluck('FacilityLevel');
        // });

        // $FacilityTypes = Cache::remember('facilityTypes', 60, function() {
        //     return MalavaParticipant::distinct()->pluck('FacilityType');
        // });

        // $FacilityAgents = Cache::remember('facilityAgents', 60, function() {
        //     return MalavaParticipant::distinct()->pluck('FacilityAgent');
        // });

        
        $poll_answers = PollAnswer::where('poll_id', $poll->id)
                                   ->whereIn('poll_question_id', $question_ids)
                                    ->get();

       $participant_answers = ParticipantAnswer::where('poll_id', $poll->id)->get();
        $participant_ids = $participant_answers->pluck('participant_id')->toArray();

        $user = MalavaParticipant::find($id);

        $pollingStations = PollingStation::all();
        
        if (!$user || $user->role_id != 4 ) {
            return back()->with('message', 'Participant already made poll');
        }
        
            return view('pages.polls.participant_answer.create', compact(['poll','agents', 
            'counties','pollingStations','poll_questions', 'poll_answers', 'user', 'participant_answers']));
            
            
    }
    public function addPoll($id)
    {
        $poll = Poll::find(2);
        $agents = User::where('role_id', 6)->get();
        $poll_questions = PollQuestion::where('poll_id', $poll->id)->get();
        $question_ids = $poll_questions->pluck('id')->toArray();
        
        $poll_answers = PollAnswer::where('poll_id', $poll->id)
                                   ->whereIn('poll_question_id', $question_ids)
                                    ->get();

       $participant_answers = ParticipantAnswer::where('poll_id', $poll->id)->get();
        $participant_ids = $participant_answers->pluck('participant_id')->toArray();

        $user = MalavaParticipant::find($id);
        if (!$user || $user->role_id != 4 || in_array($user->id, $participant_ids)) {
            return back()->with('message', 'Participant already made poll');
        }
        
            return view('pages.polls.participant_answer.create', compact(['poll','agents',  'poll_questions', 'poll_answers', 'user', 'participant_answers']));
            
            
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update2(Request $request){
        dd($request->all());

    }
    public function store(Request $request)
    {
        // dd($request->all());



            //     // Validate the input data
            //         $validator = Validator::make($request->all(), [
            //             'question_1' => 'required',
            //         ]);
        

            // // If validation fails, return back with errors and input
            // if ($validator->fails()) {
            //     // dd($request->all(), 'fail', $validator->messages());
            //     return back()
            //         ->withErrors($validator)
            //         ->withInput();
            // }
            
        try {
            DB::beginTransaction();
        
            // Update participant's call status and save
            $participant = MalavaParticipant::find($request->input('participant_id'));
            $participant->call_status = 1;
            $participant->called_by = auth()->user()->id;
            $participant->updated_by = auth()->user()->id;
            $participant->save();
            

        
            // Iterate over submitted data to extract question identifiers and selected answers
            foreach ($request->all() as $key => $value) {
                // Check if the submitted data corresponds to a question-answer pair
                if (strpos($key, 'question_') === 0) {
                    // Extract the question identifier (e.g., question ID or number)
                    $question_id = substr($key, strlen('question_'));
                    $question = PollQuestion::find($question_id);

                    $participant = MalavaParticipant::find($request->participant_id);
                    // dd($request->all(), $value, $question, $participant);
                    $participant->call_status = 1;
                    $participant->save();
        
                    // Save the question identifier and the selected answer to the database
                    $existingParticipantAnswer = ParticipantAnswer::where('poll_id', $request->input('poll_id'))
                    ->where('participant_id', $participant->id)
                    ->where('poll_question_id', $question_id)
                    ->get();
                
                if ($existingParticipantAnswer->isEmpty()) {
                    $participant->save();
                        $participant_answer = new ParticipantAnswer();
                        $participant_answer->participant_id = $participant->id;
                        $participant_answer->poll_id = $request->input('poll_id');
                        $participant_answer->poll_question_id = $question_id;
                        if($question->question_type == "multiple"){
                            $participant_answer->answer_id = $value;
                        }else{
                        $participant_answer->answer = $value;
                        }
                        $participant_answer->comment = $request->input('comment');
                        $participant_answer->followUpDate = $request->input('followUpDate');
                        $participant_answer->created_by = auth()->user()->id;
                        $participant_answer->save();
                    }
                }
            }
        
            DB::commit();
            return back()->with('success', 'Poll saved successfully');
        
        } catch (QueryException $e) {
            dd($e);
            DB::rollBack();
            return back()->with('error', 'Poll not saved');
        
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());


        try {
            DB::beginTransaction();
        
            // Update participant's call status and save
            $participant = MalavaParticipant::find($request->input('participant_id'));
            $participant->call_status = 1;
            $participant->called_by = auth()->user()->id;
            $participant->updated_by = auth()->user()->id;
            // $participant->save();

        
            // Iterate over submitted data to extract question identifiers and selected answers
            foreach ($request->all() as $key => $value) {
                // Check if the submitted data corresponds to a question-answer pair
                if (strpos($key, 'question_') === 0) {
                    // Extract the question identifier (e.g., question ID or number)
                    $question_id = substr($key, strlen('question_'));
        
                    // Save the question identifier and the selected answer to the database
                    $existingParticipantAnswer = ParticipantAnswer::where('poll_id', $request->input('poll_id'))
                    ->where('participant_id', $participant->id)
                    ->where('poll_question_id', $question_id)
                    ->get();
                
                if ($existingParticipantAnswer->isEmpty()) {
                    $participant->save();
                        $participant_answer = new ParticipantAnswer();
                        $participant_answer->participant_id = $participant->id;
                        $participant_answer->poll_id = $request->input('poll_id');
                        $participant_answer->poll_question_id = $question_id;
                        $participant_answer->answer_id = $value;
                        $participant_answer->comment = $request->input('comment');
                        $participant_answer->followUpDate = $request->input('followUpDate');
                        $participant_answer->created_by = auth()->user()->id;
                        $participant_answer->save();
                    }
                }
            }
        
            DB::commit();
            return back()->with('success', 'Poll saved successfully');
        
        } catch (QueryException $e) {
            dd($e);
            DB::rollBack();
            return back()->with('error', 'Poll not saved');
        
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
