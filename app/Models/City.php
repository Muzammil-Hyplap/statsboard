<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function state(){
        return $this->belongsTo(State::class);
    }

    public function country(){
        return $this->belongsto(Country::class);
    }

    public function user ():HasMany
    {
        return $this->hasMany(User::class);
    }
}
