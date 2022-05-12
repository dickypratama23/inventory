<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(Transaksi_detail::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function invr()
    {
        return $this->belongsTo(Transaksi::class, 'inv_relation', 'invoice');
    }
}
