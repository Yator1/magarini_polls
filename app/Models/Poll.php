<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;
    // protected $fillable = ['name', 'status'];
    protected $guarded =[];


    public function questions()
    {
        return $this->hasMany(PollQuestion::class);
    }

    public function answers()
    {
        return $this->hasManyThrough(PollAnswer::class, PollQuestion::class);
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
