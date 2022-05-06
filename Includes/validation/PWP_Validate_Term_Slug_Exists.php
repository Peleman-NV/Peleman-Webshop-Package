<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Validate_Term_Slug_Exists extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): PWP_I_Response
    {
        $slug = $request->get_slug() ?: null;
        if (empty($slug) || !$service->is_slug_in_use($slug)) {
            return PWP_Response::failure("term {$service->get_taxonomy_name()} with slug {$slug} does not exist");
        }
        return $this->handle_next($service, $request);
    }
}
