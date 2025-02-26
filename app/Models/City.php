<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\State;

class City extends Model
{
    public function state(){
        return $this->belongsTo(state::class);
    }

    public function country(){
        return $this->belongsto(country::class);
    }
}
