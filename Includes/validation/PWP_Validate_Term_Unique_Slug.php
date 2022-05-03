<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;

class PWP_Validate_Term_Unique_Slug extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request): bool
    {
        echo ("<p>validating slug uniqueness...</p>");
        $slug = $request->get_slug();
        if ($this->service->does_slug_exist($slug)) {
            echo ('<p>slug already in use</p>');
            return false;
        }
        echo ('<p>slug is unique</p>');
        return $this->handle_next($request);
    }
}
