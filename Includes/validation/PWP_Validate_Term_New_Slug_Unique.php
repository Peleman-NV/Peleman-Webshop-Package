<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Validate_Term_New_Slug_Unique extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): PWP_I_Response
    {
        $slug = $request->get_new_slug();

        if (!is_null($slug) && $service->is_slug_in_use($slug)) {
            return PWP_Response::failure("{$slug} is already in use for a {$service->get_taxonomy_name()} term");
        }
        return $this->handle_next($service, $request);
    }
}
