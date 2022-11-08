<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\handlers\services\Term_SVC;
use PWP\includes\wrappers\Term_Data;
use PWP\includes\utilities\notification\I_Notification;

class Validate_Term_Slug_Unique extends Abstract_Term_Handler
{
    public function handle(Term_Data $request, I_Notification $notification): bool
    {
        $slug = $request->get_slug();
        if ($this->service->is_slug_in_use($slug)) {
            $notification->add_error(
                __("Slug not unique", PWP_TEXT_DOMAIN),
                sprintf(
                    __("%s is already in use for a %s term", PWP_TEXT_DOMAIN),
                    $slug,
                    $this->service->get_taxonomy_name()
                )
            );
        }
        return $this->handle_next($request, $notification);
    }
}
