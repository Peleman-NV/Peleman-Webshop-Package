<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;

class PWP_Validate_Term_Translation_Data extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request): bool
    {
        if ($request->has_translation_data()) {
            return $this->validate_translation_data($request);
        }

        return $this->handle_next($request);
    }

    public function validate_translation_data(PWP_Term_Data $request): bool
    {
        $translationData = $request->get_translation_data();
        if (!$this->service->is_slug_in_use($translationData->get_english_slug())) {
            return false;
        }
        if (!$translationData->get_language_code()) {
            return false;
        }

        $this->handle_next($request);
    }
}
