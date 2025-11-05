<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollAnswer extends Model
{
    use HasFactory;
    // protected $fillable = ['poll_id', 'poll_question_id', 'answer', 'status'];
    protected $guarded =[];


    public function question()
    {
        return $this->belongsTo(PollQuestion::class);
    }
    public function pollQuestion()
    {
        return $this->belongsTo(PollQuestion::class, 'poll_question_id');
    }

    public function participantAnswers()
    {
        return $this->hasMany(ParticipantAnswer::class, 'answer_id')
                    ->whereHas('pollQuestion', function ($query) {
                        $query->whereColumn('poll_questions.id', 'participants_answers.poll_question_id')
                              ->whereColumn('poll_questions.poll_id', 'participants_answers.poll_id');
                    });
    }
}
    
