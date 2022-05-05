<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;

class PWP_Validate_Term_New_Slug_Unique extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): bool
    {
        $slug = $request->get_new_slug();
        if (is_null($slug)) {
            return $this->handle_next($service, $request);
        }
        if ($service->is_slug_in_use($slug)) {
            return false;
        }
        return $this->handle_next($service, $request);
    }
}
