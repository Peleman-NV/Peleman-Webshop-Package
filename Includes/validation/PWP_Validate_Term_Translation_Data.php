<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Validate_Term_Translation_Data extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): PWP_I_Response
    {
        if ($request->has_translation_data()) {
            $translationData = $request->get_translation_data();
            if (!$service->is_slug_in_use($translationData->get_english_slug())) {
                return PWP_Response::failure("translation data for term with slug {$request->get_slug()} does not have a valid or existing english parent slug.");
            }
            if (!$translationData->get_language_code()) {
                return PWP_Response::failure("translation data for term with slug {$request->get_slug()} lacks a language code");
            }
        }

        return $this->handle_next($service, $request);
    }
}
