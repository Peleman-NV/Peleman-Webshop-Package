<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use WP_Term;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\validation\PWP_Validation_Handler;
use PWP\includes\validation\PWP_Abstract_Term_Handler;
use PWP\includes\utilities\notification\PWP_Notification;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\validation\PWP_Validate_Term_Slug_Exists;
use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\validation\PWP_Validate_Term_Parent_Exists;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\validation\PWP_Validate_Term_New_Slug_Unique;
use PWP\includes\validation\PWP_Validate_Term_Slug_Characters;
use PWP\includes\validation\PWP_Validate_Term_Translation_Data;
use PWP\includes\validation\PWP_Validate_Term_New_Slug_Characters;

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

        $this->handler = new PWP_Validation_Handler($this->service);
        $this->handler
            ->set_next(new PWP_Validate_Term_Slug_Exists($this->service))
            ->set_next(new PWP_Validate_Term_Slug_Characters($this->service))
            ->set_next(new PWP_Validate_Term_Parent_Exists($this->service))
            ->set_next(new PWP_Validate_Term_Translation_Data($this->service))
            ->set_next(new PWP_Validate_Term_New_Slug_Unique($this->service))
            ->set_next(new PWP_Validate_Term_New_Slug_Characters($this->service));
    }

    final public function do_action(): PWP_I_Response
    {
        $notification = new PWP_Notification();
        if (!$this->validate_data($notification)) {
            return $notification;
        }

        $originalTerm = $this->service->get_item_by_slug($this->slug);

        $updatedTerm = $this->update_term($originalTerm);

        $this->configure_translation_table($updatedTerm);
        $this->configure_seo_data($updatedTerm);

        return new PWP_Success_Notice(
            "update successful",
            "{$this->service->get_taxonomy_name()} with slug {$this->slug} has been successfully updated",
            (array)$updatedTerm->data
        );
    }

    final public function undo_action(): PWP_I_Response
    {
        $notification = new PWP_Notification();
        return $notification->add_error(
            "method not implemented",
            "method " . __METHOD__ . " not implemented"
        );
    }

    protected function update_term(WP_Term $original): \WP_TERM
    {
        $this->data->set_parent($this->get_parent($original));

        return $this->service->update_item(
            $original,
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

    protected function validate_data(PWP_I_Notification $notification): bool
    {
        return $this->handler->handle($this->data, $notification);
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
