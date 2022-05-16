<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validate_Term_Translation_Data extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        if ($request->has_translation_data()) {
            $translationData = $request->get_translation_data();
            if (!$this->service->is_slug_in_use($translationData->get_english_slug())) {
                $notification->add_error(
                    __("Translation original not found", PWP_TEXT_DOMAIN),
                    "Translation data for term with slug {$request->get_slug()} does not have a valid or existing english parent slug."
                );
            }
            if (!$translationData->get_language_code()) {
                $notification->add_error(
                    __("Language code missing", PWP_TEXT_DOMAIN),
                    "Translation data for term with slug {$request->get_slug()} lacks a language code"
                );
            }
        }

        return $this->handle_next($request, $notification);
    }
}
