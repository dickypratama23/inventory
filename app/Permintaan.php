<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
