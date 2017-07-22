<?php


namespace Models;

use \Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $table = 'bots';

    protected $guarded = ['id'];

    public $timestamps = false;
}