<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use WP_Term;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\validation\PWP_Abstract_Term_Handler;
use PWP\includes\validation\PWP_Validate_Term_Slug_Characters;
use PWP\includes\validation\PWP_Validate_Term_Slug_Exists;
use PWP\includes\validation\PWP_Validate_Term_Translation_Data;

class PWP_Update_Term_Command implements PWP_I_Command
{
    protected PWP_Term_SVC $service;
    protected string $slug;
    protected string $lang;
    protected PWP_Term_Data $data;

    protected bool $canChangeParent;

    protected PWP_Abstract_Term_Handler $handler;

    public function __construct(PWP_Term_SVC $service, PWP_Term_Data $data, bool $canChangeParent = false)
    {
        $this->service = $service;
        $this->data = $data;
        $this->slug = $data->get_slug() ?: '';
        $this->lang = 'en';

        $this->canChangeParent = $canChangeParent;

        $this->handler = new PWP_Validate_Term_Slug_Exists();
        $this->handler
            ->set_next(new PWP_Validate_Term_Slug_Characters())
            ->set_next(new PWP_Validate_Term_Translation_Data());
    }

    final public function do_action(): PWP_I_Response
    {
        if ($this->validate_data()) {
            $originalTerm = $this->service->get_item_by_slug($this->slug);

            $updatedTerm = $this->update_term($originalTerm);

            $this->configure_translation_table($updatedTerm);
            $this->configure_seo_data($updatedTerm);

            return new PWP_Response(
                "{$this->service->get_taxonomy_name()} with slug {$this->slug} has been successfully updated",
                (array)$updatedTerm->data
            );
        }
        return new PWP_Response("{$this->service->get_taxonomy_name()} with slug {$this->slug} cannot be updated.");
    }

    final public function undo_action(): PWP_I_Response
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    protected function update_term(WP_Term $original): \WP_TERM
    {
        $this->data->set_parent($this->get_parent($original));

        return $this->service->update_item(
            $original,
            $this->service->get_taxonomy(),
            $this->data->to_array()
        );
    }

    protected function configure_translation_table(WP_Term $term): void
    {
        if ($this->data->has_translation_data()) {
            $translationData = $this->data->get_translation_data();
            $original = $this->service->get_item_by_slug($translationData->get_english_slug());
            if (is_null($original)) {
                return;
            }
            $this->service->configure_translation(
                $term,
                $original,
                $translationData->get_language_code(),
                $this->service->get_sourcelang()
            );
        }
    }

    protected function configure_SEO_data(WP_Term $term): void
    {
        $seoData = $this->data->get_seo_data();
        if (!empty($seoData)) {
            $this->service->configure_SEO_data($term, $seoData);
        }
    }

    protected function validate_data(): bool
    {
        return $this->handler->handle($this->service, $this->data);
    }

    final protected function get_parent(WP_Term $original): int
    {
        if ($this->canChangeParent || empty($original->parent)) {
            $parent = $this->service->get_item_by_slug($this->data->get_parent_slug());
            return $parent ? (int)$parent->term_id : 0;
        }
        return $original->parent;
    }
}
