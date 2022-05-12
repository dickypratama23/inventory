<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DemandProses extends Model
{
    protected $table = 'd23_pp_proses';
    protected $guarded = [];
    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
