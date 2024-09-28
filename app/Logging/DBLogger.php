<?php

namespace App\Logging;

use Monolog\Logger;

class DBLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(array $config): Logger
    {
        return new Logger('Database', [
            new DBLoggingHandler(),
        ]);
    }
}
