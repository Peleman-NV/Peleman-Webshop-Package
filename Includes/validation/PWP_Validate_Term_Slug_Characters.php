<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\validation\PWP_Abstract_Term_Handler;

class PWP_Validate_Term_Slug_Characters extends PWP_Abstract_Term_Handler
{
    private string $expression;

    public function __construct()
    {
        $this->expression = '/^[a-z0-9_]+(-[a-z0-9_]+)*$/';
        parent::__construct();
    }

    public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): bool
    {
        $slug = $request->get_slug();

        return preg_match($this->expression, $slug)
            ? $this->handle_next($service, $request)
            : false;
    }
}
