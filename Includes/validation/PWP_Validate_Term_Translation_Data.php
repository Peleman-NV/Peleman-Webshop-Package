<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;

class PWP_Validate_Term_Translation_Data extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): bool
    {
        if ($request->has_translation_data()) {
            $translationData = $request->get_translation_data();
            if (!$service->is_slug_in_use($translationData->get_english_slug())) {
                return false;
            }
            if (!$translationData->get_language_code()) {
                return false;
            }
        }

        return $this->handle_next($service, $request);
    }
}
