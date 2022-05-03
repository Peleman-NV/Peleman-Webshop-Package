<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use WP_Term;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

class PWP_Update_Term_Command implements PWP_I_Command
{
    protected PWP_Term_SVC $service;
    protected string $slug;
    protected string $lang;
    protected PWP_Term_Data $data;

    public function __construct(PWP_Term_SVC $service, PWP_Term_Data $data)
    {
        $this->service = $service;
        $this->data = $data;
        $this->slug = $data->get_slug();
        $this->lang = 'en';
    }

    final public function do_action(): PWP_I_Response
    {
        $term = $this->update_item_by_slug();
        $this->configure_translation_table($term);
        // $this->configure_seo_data($term);

        return new PWP_Response(
            "{$this->service->get_beauty_name()} with slug {$this->slug} has been successfully updated",
            (array)$term->data
        );
    }

    final public function undo_action(): PWP_I_Response
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    protected function update_item_by_slug(): \WP_TERM
    {
        $term = $this->service->get_item_by_slug($this->slug, $this->lang);

        if (!empty($term->parent) || $term->parent === 0) {
            $this->data->set_parent($term->parent);
        } else {
            $parent = $this->service->get_item_by_slug($this->data->get_parent_slug(), $this->lang);
            $this->data->set_parent($parent ? $parent->term_id : 0);
        }
        return $this->service->update_item($term, $this->data->to_array());
    }

    protected function configure_translation_table(WP_Term $term): void
    {
        echo ('okay, this might be our issue');
        var_dump($term);
        // $this->service->set_translation_data($term, $term, $this->lang, null);
    }

    protected function configure_seo_Data(WP_Term $term): void
    {
        $seoData = $this->data->get_seo_data();
        if (!empty($seoData)) {
            $this->service->set_seo_data($term, $seoData);
        }
    }
}
