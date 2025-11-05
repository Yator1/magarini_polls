<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollQuestion extends Model
{
    use HasFactory;
    // protected $fillable = ['poll_id', 'question', 'status'];
    protected $guarded =[];


    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function answers()
    {
        return $this->hasMany(PollAnswer::class);
    }
    public function singleAanswers()
    {
        return $this->hasMany(ParticipantAnswer::class, 'answer');
    }

    public function participantAnswers()
    {
        return $this->hasMany(ParticipantAnswer::class);
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

// php artisan migrate:refresh --path=database/migrations/2024_04_15_090340_create_polls_table.php
// php artisan migrate:refresh --path=database/migrations/2024_04_15_090428_create_poll_questions_table.php
// php artisan migrate:refresh --path=database/migrations/2024_04_15_090504_create_poll_answers_table.php
// php artisan migrate:refresh --path=database/migrations/2024_04_15_090522_create_participants_answers_table.php
