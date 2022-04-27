<?php

declare(strict_types=1);

namespace PWP\includes\exceptions;

class PWP_Not_Found_Exception extends PWP_API_Exception
{
    public function __construct(string $message = '', \Throwable $previous = null)
    {
        parent::__construct(!empty($message) ? $message : 'object not found', 404, $previous);
    }
}