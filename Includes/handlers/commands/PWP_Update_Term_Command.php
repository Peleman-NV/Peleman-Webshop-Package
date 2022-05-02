<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\exceptions\PWP_API_Exception;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\wrappers\PWP_Term_Data;
use WP_Term;

class PWP_Update_Term_Command implements PWP_I_Command
{
    protected PWP_Term_SVC $service;
    protected string $slug;
    protected PWP_Term_Data $updateData;

    public function __construct(PWP_Term_SVC $service, PWP_Term_Data $updateData)
    {
        $this->service = $service;
        $this->updateData = $updateData;
        $this->slug = $updateData->get_slug();
    }

    final public function do_action(): PWP_I_Response
    {
        try {
            echo 'I ';
            $term = $this->update_item_by_slug();
            echo 'am ';
            $this->configure_translation_table($term);
            echo 'updated ';
            $this->configure_seo_data($term);
            echo 'now!';

            return new PWP_Response(
                "{$this->service->get_beauty_name()} with slug {$this->slug} has been successfully updated",
                (array)$term->data
            );
        } catch (PWP_API_Exception $exception) {
            return new PWP_Error_Response("error when updating category {$this->slug} ", $exception);
        }
    }

    final public function undo_action(): PWP_I_Response
    {
        return new PWP_Response("not implemented");
    }

    protected function update_item_by_slug(): \WP_TERM
    {
        $term = $this->service->get_item_by_slug($this->slug);

        if (!empty($term->parent) || $term->parent === 0) {
            $this->updateData->set_parent($term->parent);
        } else {
            $parent = $this->service->get_item_by_slug($this->updateData->get_parent_slug());
            $this->updateData->set_parent($parent ? $parent->term_id : 0);
        }
        return $this->service->update_item($term, $this->updateData->to_array());
    }

    protected function configure_translation_table(WP_Term $term): void
    {
        $this->service->set_translation_data($term, $term, 'en');
    }

    protected function configure_seo_Data(WP_Term $term): void
    {
        $seoData = $this->updateData->get_seo_data();
        if ($seoData) {
            $this->service->set_seo_data($term, $seoData);
        }
    }
}
