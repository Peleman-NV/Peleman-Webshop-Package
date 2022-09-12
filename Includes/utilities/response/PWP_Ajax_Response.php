<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

abstract class PWP_Ajax_Response implements PWP_I_Response
{
    /**
     * TODO: build class into proper AJAX response class
     * 
     */
    public function to_array(): array
    {
        return [];
    }
}
