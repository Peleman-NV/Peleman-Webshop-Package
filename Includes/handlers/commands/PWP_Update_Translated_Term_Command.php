<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use WP_Term;

final class PWP_Update_Translated_Term_Command extends PWP_Update_Term_Command
{
    protected function update_item_by_slug(): \WP_Term
    {
        return parent::update_item_by_slug();
    }

    protected function configure_translation_table(WP_Term $term): void
    {
        $translationData = $this->updateData->get_translation_data();
        $Englishparent =  $this->service->get_item_by_slug($translationData->get_english_slug());
        $this->service->set_translation_data($term, $Englishparent, $translationData->get_language_code(), 'en');
    }
}
