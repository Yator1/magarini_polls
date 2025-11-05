<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    // Protect all fields from mass assignment
    protected $guarded = [];

    // Correct syntax for defining the table name
    protected $table = 'health_facilities';
}
