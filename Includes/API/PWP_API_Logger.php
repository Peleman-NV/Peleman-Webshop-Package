<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\utilities\PWP_ILogger;

/**
 * utility class to log events in the running of an api call
 * 
 * the logger is meant to retain data about the events happening within an api call.
 * as things go wrong, but running continues, the logger will retain info about such events and return them to the user
 */
class PWP_API_Logger implements PWP_ILogger
{
    private array $logs;


    public function add_log(string $class, int $line, string $message): void
    {
        $this->logs[] = PWP_API_Log::log($class, $line, $message);
    }

    public function add_notice_log(string $class, int $line, string $message): void
    {
        $this->logs[] = PWP_API_Log::log_notice($class, $line, $message);
    }

    public function add_warning_log(string $class, int $line, string $message): void
    {
        $this->logs[] = PWP_API_Log::log_warning($class, $line, $message);
    }

    public function get_logs(): array
    {
        $logs = array_map(function ($item) {
            return (string)$item;
        }, $this->logs);

        return $logs;
    }
}
