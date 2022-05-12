<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokoPinjam extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'lent_mst';
    protected $guarded = [];
    public $timestamps = false;
}
