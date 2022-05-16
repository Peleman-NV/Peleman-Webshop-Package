<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validate_Term_Parent_Exists extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        $parentSlug = $request->get_parent_slug() ?: '';
        if (!empty($parentSlug) && !$this->service->is_slug_in_use($parentSlug)) {
            $notification->add_error(
                __("Parent term not found", PWP_TEXT_DOMAIN),
                "Parent term {$this->service->get_taxonomy_name()} with slug {$parentSlug} does not exist"
            );
        }
        return $this->handle_next($request, $notification);
    }
}
