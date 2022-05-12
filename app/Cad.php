<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cad extends Model
{
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
