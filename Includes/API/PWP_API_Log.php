<?php

declare(strict_types=1);

namespace PWP\includes\API;

class PWP_API_Log
{
    private string $type;
    private string $class;
    private int $line;
    private string $message;

    private function __construct(string $type, string $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    public static function info(string $message): self
    {
        return new PWP_API_Log('INFO', $message);
    }

    public static function notice(string $message): self
    {
        return new PWP_API_Log('NOTICE', $message);
    }

    public static function warning(string $message): self
    {
        return new PWP_API_Log('WARNING', $message);
    }

    public static function error(string $class, int $line, string $message): self
    {
        $notice = sprintf(
            "%s line %s: %s",
            $class,
            $line,
            $message,
        );
        return new PWP_API_Log('ERROR', $notice);
    }

    public function __toString()
    {
        return sprintf(
            "[%s] %s",
            $this->type,
            $this->message,
        );
    }
}
