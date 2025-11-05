<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

  
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

    
    
    public function Calls()
    {
        return $this->hasMany(MalavaParticipant::class, 'called_by');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }

    
}
