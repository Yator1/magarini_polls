<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingStation extends Model
{
    use HasFactory;

    protected $guarded = [];

      public function users()
    {
        return $this->hasMany(User::class, 'pstation_code');
    }
      public function mobilizers()
    {
        return $this->hasMany(MalavaParticipant::class, 'pstation_code');
    }
}
