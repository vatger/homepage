<?php

namespace App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DBLoggingHandler extends AbstractProcessingHandler
{

    protected function write(LogRecord $record): void
    {
       
    }
}
