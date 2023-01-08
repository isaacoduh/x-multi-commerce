<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentArea extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function state(){
        return $this->belongsTo(ShipmentState::class,'state_id','id');
    }
}
