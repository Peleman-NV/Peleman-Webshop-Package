<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\wrappers\PWP_Translation_Data;
use WP_Term;

final class PWP_Update_Translated_Term_Command extends PWP_Update_Term_Command
{
    private string $englishSlug;
    private ?string $sourceLang;

    public function __construct(PWP_Term_SVC $service, PWP_Term_Data $data)
    {
        parent::__construct($service, $data);

        $this->englishSlug = $data->get_translation_data()->get_english_slug();
        $this->lang = $data->get_translation_data()->get_language_code();
        $this->sourceLang = 'en';
    }

    protected function update_item_by_slug(): \WP_Term
    {
        $term = $this->service->get_item_by_slug($this->slug);

        return $this->service->update_item($term, $this->data->to_array());
    }
    protected function configure_translation_table(WP_Term $term): void
    {
        $englishParent = $this->service->get_item_by_slug($this->englishSlug);
        $this->service->set_translation_data(
            $term,
            $englishParent,
            $this->lang,
            $this->sourceLang
        );
    }
}
