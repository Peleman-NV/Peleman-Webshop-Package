<?php

declare(strict_types=1);

namespace PWP\includes\exceptions;

class PWP_Not_Implemented_Exception extends \Exception
{
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($message, 501, $previous);        
    }
}
