<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class State extends Model
{
    public function country (){
        return $this->belongsTo(country::class);
    }

    public function city() {
        return $this->hasMany(city::class);
    }
}
