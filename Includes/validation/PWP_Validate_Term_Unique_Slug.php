<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;

class PWP_Validate_Term_Unique_Slug extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request): bool
    {
        $slug = $request->get_slug();
        if ($this->service->does_slug_exist($slug)) {
            throw new PWP_Invalid_Input_Exception("{$this->service->get_beauty_name()} with slug {$slug} already exists. Slugs must be unique!");
            return false;
        }

        return $this->handle_next($request);
    }
}
