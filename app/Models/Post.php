<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function postcategory()
    {
        return $this->belongsTo(PostCategory::class,'category_id','id');
    }
}
