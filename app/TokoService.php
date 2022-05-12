<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokoService extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'service_mst';
    protected $guarded = [];
    public $timestamps = false;


    
}
