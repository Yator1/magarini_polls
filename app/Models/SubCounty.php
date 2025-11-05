<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCounty extends Model
{
    use HasFactory;

    protected $guarded = [];



    // public function users()
    // {
    //     return $this->hasMany(User::class, 'sub_county_id');
    // }
    public function users()
    {
        return $this->hasMany(Mobilizer::class, 'sub_county_id');
    }
    public function mobilizers()
    {
        return $this->hasMany(Mobilizer::class, 'sub_county_id');
    }

    
    public function wards()
    {
        return $this->hasMany(Ward::class, 'subcounty_id');
    }

    public function county()
    {
        return $this->belongsTo(Counties::class, 'county_id');
    }



    // public function county()
    // {
    //     return $this->belongsTo(County::class, 'county_id');
    // }
}
