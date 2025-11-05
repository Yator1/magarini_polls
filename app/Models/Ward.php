<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subcounty()
    {
        return $this->belongsTo(SubCounty::class, 'subcounty_id');
    }
    //  public function users()
    // {
    //     return $this->hasMany(User::class, 'ward_id');
    // }
     public function users()
    {
        return $this->hasMany(Mobilizer::class, 'ward_id');
    }
     public function mobilizers()
    {
        return $this->hasMany(Mobilizer::class, 'ward_id');
    }

    // public function county()
    // {
    //     return $this->belongsTo(County::class, 'county_id');
    // }
}
