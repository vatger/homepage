<?php

namespace App\Models\Tech;

use Illuminate\Database\Eloquent\Model;

class SysLog extends Model
{
    protected $table = 'syslog';

    protected $guarded = [];

    public static function Log($params)
    {
        $log = new self();
        $log->account_id = $params['user_id'];
        $log->path = $params['path'];
        $log->method = $params['method'];
        $log->save();
    }

    public static function LogException($params)
    {
        $log = new self();
        $log->user_id = $params['user_id'];
        $log->type = $params['type'] === null ? 'log' : $params['type'];
        $log->path = $params['path'];
        $log->method = $params['method'];
        $log->stack_trace = $params['stack_trace'];
        $log->message = $params['message'];
        $log->file = $params['file'];
        $log->line = $params['line'];
        $log->save();
    }
}
