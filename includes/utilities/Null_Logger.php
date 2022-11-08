<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

class Null_Logger implements ILogger
{
    public function add_log(string $class, int $line, string $message): void
    {
        return;
    }

    public function add_notice_log(string $class, int $line, string $message): void
    {
        return;
    }

    public function add_warning_log(string $class, int $line, string $message): void
    {
        return;
    }

    public function get_logs(): array
    {
        return array();
    }
}
