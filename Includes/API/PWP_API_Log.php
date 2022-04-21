<?php

declare(strict_types=1);

namespace PWP\includes\API;

class PWP_API_Log
{
    private string $type;
    private string $class;
    private int $line;
    private string $message;

    private function __construct(string $type, string $class, int $line, string $message)
    {
        $this->type = $type;
        $this->class = $class;
        $this->line = $line;
        $this->message = $message;
    }

    public static function log(string $class, int $line, string $message) : self
    {
        return new PWP_API_Log('LOG', $class, $line, $message);
    }

    public static function log_notice(string $class, int $line, string $message) : self
    {
        return new PWP_API_Log('NOTICE', $class, $line, $message);
    }

    public static function log_warning(string $class, int $line, string $message) : self
    {
        return new PWP_API_Log('WARNING', $class, $line, $message);
    }

    public function __toString()
    {
        return sprintf(
            "[%s]: %s: %d - %s",
            $this->type,
            $this->class,
            $this->line,
            $this->message
        );
    }
}
