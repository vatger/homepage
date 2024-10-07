<?php

namespace App\Logging;

use App\Models\Tech\SysLog;
use Illuminate\Support\Arr;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DBLoggingHandler extends AbstractProcessingHandler
{

    protected function write(LogRecord $record): void
    {
        try {
            $log = new SysLog();
            $log->type = $record->level->getName();
            $log->method = 'logger';
            $log->message = $record->message;
            $log->stack_trace = implode("\n", $record->context);
            $log->channel = $record->channel;
            $log->save();
        } catch (\Exception $exception) {
            \Log::channel('stack')->log($record->level, $record->message, $record->context);
        }
    }
}
