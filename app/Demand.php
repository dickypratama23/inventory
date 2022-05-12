<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    protected $table = 'd23_pp';
    protected $guarded = [];
    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
