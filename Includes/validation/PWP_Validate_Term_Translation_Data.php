<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\wrappers\PWP_Term_Data;

class PWP_Validate_Term_Translation_Data extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request): bool
    {
        $translationData = $request->get_translation_data();
        if (!$this->service->does_slug_exist($translationData->get_english_slug())) {
            throw new PWP_Invalid_Input_Exception("{$this->service->get_beauty_name()} with slug {$translationData->get_english_slug()} already exists. Slugs must be unique!");
            return false;
        }
        if (!$translationData->get_language_code()) {
            throw new PWP_Invalid_Input_Exception("language code given for {$request->get_slug()} is not valid!");
            return false;
        }

        return $this->handle_next($request);
    }
}
