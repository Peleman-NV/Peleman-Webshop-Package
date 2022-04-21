<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

interface PWP_ILogger
{
    public function add_log(string $class, int $line, string $message): void;
    public function add_notice_log(string $class, int $line, string $message): void;
    public function add_warning_log(string $class, int $line, string $message): void;

    public function get_logs(): array;
}
