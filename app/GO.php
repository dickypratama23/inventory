<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GO extends Model
{
    protected $guarded = [];
    
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
