<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MalavaParticipant extends Model
{
  
    protected $guarded = [];


  
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }



    public function CountyName()
    {
        return $this->belongsTo(Counties::class, 'county_id');
    }
    public function SubCounty()
    {
        return $this->belongsTo(SubCounty::class, 'sub_county_id');
    }

    public function Ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function PollingStations()
    {
        return $this->belongsTo(PollingStation::class, 'pstation_code');
    }


    public function participantAnswers()
    {
        return $this->hasMany(ParticipantAnswer::class, 'participant_id');
    }
    public function CalledBy()
    {
        return $this->belongsTo(User::class, 'called_by');
    }

    
}
