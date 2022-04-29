<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\exceptions\PWP_Resource_Already_Exists_Exception;
use PWP\includes\wrappers\PWP_Translation_Data;

final class PWP_Create_Term_Command implements PWP_I_Command
{
    private PWP_Term_SVC $service;
    private string $slug;
    private PWP_Term_Data $creationData;

    public function __construct(PWP_Term_SVC $service, string $slug, PWP_Term_Data $data)
    {
        $this->service = $service;
        $this->slug = $slug;
        $this->creationData = $data;
    }

    public function do_action(): PWP_I_Response
    {
        try {
            if ($this->service->get_item_by_slug($this->slug)) {
                throw new PWP_Resource_Already_Exists_Exception("{$this->service->get_beauty_name()} with the slug {$this->slug} already exists. Slugs should be unique to avoid confusion.");
            }
            $term = $this->create_term();
            return new PWP_Response("successfully created category {$term->slug}", (array)$term->data);
        } catch (PWP_API_Exception $exception) {
            return new PWP_Error_Response("error when creating category {$this->slug} ", $exception);
        }
    }

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Response("not implemented");
    }

    public function create_term(): \WP_Term
    {
        if ($this->creationData->has_translation_data()) {
            $translationData = $this->creationData->get_translation_data();
            return $this->create_new_translated_term($translationData);
        }
        return $this->create_new_original_term($this->creationData);
    }

    private function create_new_original_term(): \WP_Term
    {
        $term = $this->create_new_item($this->creationData);
        if (!is_null($this->creationData->get_seo_data())) {
            $this->service->set_seo_data($term, $this->creationData->get_seo_data());
        }
        return $term;
    }

    private function create_new_translated_term(PWP_Translation_Data $translationData): \WP_Term
    {
        if (!$this->service->get_item_by_slug($translationData->get_english_slug())) {
            throw new PWP_Invalid_Input_Exception("invalid English slug {$translationData->get_english_slug()} has been passed.");
        }

        $term = $this->create_new_item($this->creationData);
        if (!is_null($this->creationData->get_seo_data())) {
            $this->service->set_seo_data($term, $this->creationData->get_seo_data());
        }

        $Englishparent =  $this->service->get_item_by_slug($translationData->get_english_slug());
        $this->service->set_translation_data($term, $Englishparent, $translationData->get_language_code());

        return $term;
    }

    private function create_new_item(PWP_Term_Data $data): \WP_Term
    {
        $parentId = $this->find_parent_id($data->get_parent_id(), $data->get_parent_slug());

        return $this->service->create_item(
            $data->get_name(),
            $data->get_slug(),
            $data->get_description(),
            $parentId
        );
    }

    private function find_parent_id(?int $id, ?string $slug): int
    {
        if (!empty($id)) {
            $parent = $this->service->get_item_by_id($id);
            if (!empty($parent)) {
                return $parent->term_id;
            }
        }

        if (!empty($slug)) {
            $parent = $this->service->get_item_by_slug($slug);
            if (!empty($parent)) {
                return $parent->term_id;
            }
        }

        return 0;
    }

    public function does_slug_exist(string $slug): bool
    {
        return !is_null($this->service->get_item_by_slug($slug));
    }
}
