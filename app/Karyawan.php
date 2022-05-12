<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'karyawan';
    protected $guarded = [];
    public $timestamps = false;
}
