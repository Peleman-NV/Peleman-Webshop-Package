<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validate_Term_New_Slug_Characters extends PWP_Abstract_Term_Handler
{
    private string $expression;

    public function __construct(PWP_Term_SVC $service)
    {
        $this->expression = '/^[a-z0-9_-]+(-[a-z0-9_-]+)*$/';
        parent::__construct($service);
    }

    public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        $slug = $request->get_new_slug();

        if (!is_null($slug) && !preg_match($this->expression, $slug)) {
            $notification->add_error(
                __("Invalid characters in slug", PWP_TEXT_DOMAIN),
                sprintf(__("Slug %s not of valid format. can only have lowercase letters, numbers, dashes, and underscores.", PWP_TEXT_DOMAIN), $slug)
            );
        }
        return
            $this->handle_next($request, $notification);
    }
}
