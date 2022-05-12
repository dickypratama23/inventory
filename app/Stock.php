<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];
    
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
