<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assembly extends Model
{
    protected $table = 'assembly';
    protected $guarded = [];
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
