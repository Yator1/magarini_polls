<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantAnswer extends Model
{
    use HasFactory;
    // protected $fillable = ['participant_id', 'poll_id', 'poll_question_id', 'answer_id', 'status'];
    protected $table = 'participants_answers';
    protected $guarded =[];

    public function participant()
    {
        return $this->belongsTo(MalavaParticipant::class, 'participant_id');
    }

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function question()
    {
        return $this->belongsTo(PollQuestion::class, 'poll_question_id');
    }
    public function pollQuestion()
    {
        return $this->belongsTo(PollQuestion::class, 'poll_question_id');
    }

    public function answer()
    {
        return $this->belongsTo(PollAnswer::class, 'answer_id');
    }
    public function QAnswer()
    {
        return $this->belongsTo(PollAnswer::class, 'answer_id');
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
