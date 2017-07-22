<?php

namespace Models;

use \Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $guarded = ['id'];

    public static function Log($msg) {
        self::create([
            'msg' => $msg
        ]);
    }
}