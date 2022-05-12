<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configures extends Model
{
    protected $table = 'consts';
    protected $guarded = [];
    public $timestamps = false;
}
