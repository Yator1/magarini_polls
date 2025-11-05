<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Mobilizer extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mobilizers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
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

    /**
     * Get the role associated with the mobilizer.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the county associated with the mobilizer.
     */
    public function CountyName()
    {
        return $this->belongsTo(Counties::class, 'county_id');
    }

    /**
     * Get the sub-county associated with the mobilizer.
     */
    public function SubCounty()
    {
        return $this->belongsTo(SubCounty::class, 'sub_county_id');
    }

    /**
     * Get the ward associated with the mobilizer.
     */
    public function Ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    /**
     * Get the polling station associated with the mobilizer.
     */
    public function PollingStations()
    {
        return $this->belongsTo(PollingStation::class, 'pstation_code', 'code');
    }

    /**
     * Get the participant answers associated with the mobilizer.
     */
    public function participantAnswers()
    {
        return $this->hasMany(ParticipantAnswer::class, 'participant_id');
    }

    /**
     * Get the mobilizers called by this mobilizer.
     */
    public function Calls()
    {
        return $this->hasMany(Mobilizer::class, 'called_by');
    }

    /**
     * Get the mobilizer who called this mobilizer.
     */
    public function CalledBy()
    {
        return $this->belongsTo(User::class, 'called_by');
    }
    public function UpdatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the market associated with the mobilizer.
     */
    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }
}