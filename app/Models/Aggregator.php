<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aggregator extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function SubCounty()
    {
        return $this->belongsTo(SubCounty::class, 'sub_county_id');
    }

    public function Ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function Aggregator()
    {
        return $this->belongsTo(Aggregator::class, 'aggregator');
    }

    public function RegistrationCentre()
    {
        return $this->belongsTo(Aggregator::class, 'registration_centre_id');
    }
    
    public function PollingStations()
    {
        return $this->belongsTo(Aggregator::class, 'polling_station_id');
    }
}
