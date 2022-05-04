<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;

class PWP_Validate_Term_Slug_Exists extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request): bool
    {
        $slug = $request->get_slug();
        if (!$this->service->is_slug_in_use($slug)) {
            return false;
        }
        return $this->handle_next($request);
    }
}
