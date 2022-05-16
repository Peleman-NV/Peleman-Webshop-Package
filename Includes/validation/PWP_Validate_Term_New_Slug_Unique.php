<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validate_Term_New_Slug_Unique extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        $slug = $request->get_new_slug();

        if (!is_null($slug) && $this->service->is_slug_in_use($slug)) {
            $notification->add_error(
                __("Slug not unique", PWP_TEXT_DOMAIN),
                "{$slug} is already in use for a {$this->service->get_taxonomy_name()} term"
            );
        }
        return $this->handle_next($request, $notification);
    }
}
