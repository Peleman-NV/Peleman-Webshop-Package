<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Validate_Term_Slug_Exists extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        $slug = $request->get_slug() ?: null;
        if (empty($slug) || !$this->service->is_slug_in_use($slug)) {
            $notification->add_error("term not found", "term {$this->service->get_taxonomy_name()} with slug {$slug} does not exist");
        }
        return $this->handle_next($request, $notification);
    }
}
