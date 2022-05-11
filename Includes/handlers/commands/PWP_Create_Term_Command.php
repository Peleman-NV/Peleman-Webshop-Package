<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use WP_Term;
use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\validation\PWP_Abstract_Term_Handler;
use PWP\includes\utilities\notification\PWP_Notification;
use PWP\includes\validation\PWP_Validate_Term_Slug_Unique;
use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\utilities\response\PWP_I_Response_Component;
use PWP\includes\validation\PWP_Validate_Term_Slug_Characters;
use PWP\includes\validation\PWP_Validate_Term_Translation_Data;

class PWP_Create_Term_Command implements PWP_I_Command
{
    protected PWP_Term_SVC $service;
    protected PWP_Term_Data $data;
    protected string $slug;
    protected string $lang;

    protected PWP_Abstract_Term_Handler $handler;

    public function __construct(PWP_Term_SVC $service, PWP_Term_Data $data)
    {
        $this->service = $service;
        $this->data = $data;
        $this->slug = $data->get_slug() ?: '';
        $this->lang = 'en';

        $this->handler = new PWP_Validate_Term_Slug_Unique($this->service);
        $this->handler
            ->set_next(new PWP_Validate_Term_Slug_Characters($this->service))
            ->set_next(new PWP_Validate_Term_Translation_Data($this->service));
    }

    final public function do_action(): PWP_I_Response_Component
    {
        $notification = new PWP_Notification();
        if (!$this->validate_data($notification)) {
            return $notification;
        }

        $term = $this->create_term();
        $this->configure_translation_table($term);
        $this->configure_seo_data($term);

        return new PWP_Success_Notice(
            "created term",
            "successfully created new term {$term->name} with slug {$term->slug}.",
            (array)$term->data
        );
    }

    public function undo_action(): PWP_I_Response_Component
    {
        return new PWP_Error_Notice(
            "method not implemented",
            "method " . __METHOD__ . " not implemented. Undo actions on database entries are not doable."
        );
    }

    final protected function create_term(): WP_Term
    {
        $parentId = $this->find_parent_id($this->data->get_parent_id(), $this->data->get_parent_slug());

        return $this->service->create_item(
            $this->data->get_name(),
            $this->data->get_slug(),
            $this->data->get_description(),
            $parentId
        );
    }

    protected function find_parent_id(?int $id, ?string $slug): int
    {
        if (!empty($id)) {
            $parent = $this->service->get_item_by_id($id);
            if (!empty($parent)) return $parent->term_id;
        }

        if (!empty($slug)) {
            $parent = $this->service->get_item_by_slug($slug);
            if (!empty($parent)) return $parent->term_id;
        }

        return 0;
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
}
