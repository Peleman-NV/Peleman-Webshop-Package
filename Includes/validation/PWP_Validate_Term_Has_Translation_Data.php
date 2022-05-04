<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;

class PWP_Validate_Term_Has_Translation_Data extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request): bool
    {
        if ($request->has_translation_data()) {
            return $this->handle_next($request);
        }
        //end chain here and return true, because previous validations went through and we don't need to go further.
        return true;
    }
}
