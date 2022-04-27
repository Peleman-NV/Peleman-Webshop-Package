<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Term;
use WP_Error;
use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\wrappers\PWP_Category_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\exceptions\PWP_Resource_Already_Exists_Exception;

include_once(ABSPATH . '/wp-admin/includes/plugin.php');

abstract class PWP_Term_Handler implements PWP_I_Handler, PWP_I_Slug_Handler
{
    protected PWP_Term_SVC $service;

    public function __construct(PWP_Term_SVC $service)
    {
        $this->service = $service;
    }

    public function create_item(PWP_Term_Data $createData, array $args = []): \WP_Term
    {
        $slug = $createData->get_slug();
        if ($this->service->get_item_by_slug($slug)) {
            throw new PWP_Resource_Already_Exists_Exception("{$this->service->get_beauty_name()} with the slug {$slug} already exists");
        }

        if (!empty($createData->get_english_slug())) {
            if (!empty($createData->get_language_code())) {
                throw new PWP_Invalid_Input_Exception("English slug has been entered, but no language code. Translations require both the slug and code.");
            }

            if (!$this->service->get_item_by_slug($createData->get_english_slug())) {
                throw new PWP_Invalid_Input_Exception("invalid English slug {$createData->get_english_slug()} has been passed.");
            }

            //create translated term
            $term = $this->create_new_item($createData);
            $this->service->set_seo_data($term, $createData->get_seo_data());

            $parent =  $this->service->get_item_by_slug($createData->get_english_slug());
            $this->service->set_translation_data($term, $parent, $createData->get_language_code());

            return $term;
        }

        //create regular term.

        $term = $this->create_new_item($createData);
        $this->service->set_seo_data($term, $createData->get_seo_data());

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

    public function get_item(int $id, array $args = []): \WP_Term
    {
        return $this->service->get_item_by_id($id, $args);
    }

    public function get_items(array $args = []): array
    {
        return $this->service->get_items($args);
    }

    public function get_item_by_slug(string $slug, array $args = []): WP_Term
    {
        return $this->service->get_item_by_slug($slug, $args);
    }

    public function update_item(int $id, array $updateData, array $args = [], bool $useNullValues = false): WP_Term
    {
        $updateData = new PWP_Term_Data($updateData);
        throw new PWP_Not_Implemented_Exception(sprintf("%s : %s: function %s not implemented!", __FILE__, __LINE__, __METHOD__));
    }

    public function delete_item(int $id, array $args = []): bool
    {
        return $this->service->delete_item($id, $args);
    }

    final public function update_item_by_slug(string $slug, array $updateData, array $args = [], bool $useNullValues = false): \WP_TERM
    {
        $term = $this->service->get_item_by_slug($slug);
        $data = new PWP_Term_Data($updateData);

        $data->set_parent_id($this->service->get_item_by_slug($data->get_parent_slug())->term_id);
        return $this->service->update_item($term, $data->to_array());
    }

    public function delete_item_by_slug(string $slug, array $args = []): bool
    {
        $term = $this->service->get_item_by_slug($slug);
        if (empty($term)) {
            throw new PWP_Not_Found_Exception("term with slug {$slug} not found");
        }

        return $this->delete_item($term->term_id, $args);
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
}
