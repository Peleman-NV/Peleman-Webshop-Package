<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

final class PWP_Update_Translated_Term_Command extends PWP_Update_Term_Command
{
    protected function update_item_by_slug(): \WP_Term
    {
        $term = parent::update_item_by_slug();

        $Englishparent =  $this->service->get_item_by_slug($this->translationData->get_english_slug());
        $this->service->set_translation_data($term, $Englishparent, $this->translationData->get_language_code());

        return $term;
    }
}
