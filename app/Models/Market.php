<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relationships
    public function subCounty()
    {
        return $this->belongsTo(SubCounty::class, 'subcounty_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    // public function users()
    // {
    //     return $this->hasMany(User::class, 'market_id');
    // }
    public function users()
    {
        return $this->hasMany(Mobilizer::class, 'market_id');
    }
    public function mobilizers()
    {
        return $this->hasMany(Mobilizer::class, 'market_id');
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