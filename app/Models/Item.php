<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name','discription','status'];

    public function scopeAllowed($query)
    {
        return $query->where('status','Allowed');
    }
}
