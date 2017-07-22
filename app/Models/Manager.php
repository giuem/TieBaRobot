<?php

namespace Models;

use \Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $table = 'manager';

    protected $guarded = ['id'];

    protected $keyType = 'string';

    public $timestamps = false;


}