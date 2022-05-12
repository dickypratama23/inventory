<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi_detail extends Model
{
    protected $guarded = [];


    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function cad()
    {
        return $this->belongsTo(Cad::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
