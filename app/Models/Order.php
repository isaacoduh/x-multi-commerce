<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function area()
    {
        return $this->belongsTo(ShipmentArea::class,'area_id','id');
    }

    public function state()
    {
        return $this->belongsTo(ShipmentState::class,'state_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
