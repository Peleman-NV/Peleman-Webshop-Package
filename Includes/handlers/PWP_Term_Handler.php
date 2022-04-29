<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Term;
use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\exceptions\PWP_Resource_Already_Exists_Exception;

include_once(ABSPATH . '/wp-admin/includes/plugin.php');

class PWP_Term_Handler implements PWP_I_Handler, PWP_I_Slug_Handler
{
    protected PWP_Term_SVC $service;

    public function __construct(PWP_Term_SVC $service)
    {
        $this->service = $service;
    }

    public function get_service(): PWP_Term_SVC
    {
        return $this->service;
    }

    public function create_item(array $createData, array $args = []): \WP_Term
    {
        $data = new PWP_Term_Data($createData);
        $slug = $data->get_slug();

        if (empty($slug)) {
            throw new PWP_Invalid_Input_Exception("the category slug is required!");
        }
        if ($this->service->get_item_by_slug($slug)) {
            throw new PWP_Resource_Already_Exists_Exception("{$this->service->get_beauty_name()} with the slug {$slug} already exists. Slugs should be unique to avoid confusion.");
        }

        if (!empty($data->get_english_slug())) {
            return $this->create_new_translated_term($data);
        }
        return $this->create_new_original_term($data);
    }

    private function create_new_original_term(PWP_Term_Data $data): \WP_Term
    {
        $term = $this->create_new_item($data);
        if (!is_null($data->get_seo_data())) {
            $this->service->set_seo_data($term, $data->get_seo_data());
        }
        return $term;
    }

    private function create_new_translated_term(PWP_Term_Data $data): \WP_Term
    {
        if (empty($data->get_language_code())) {
            throw new PWP_Invalid_Input_Exception("English slug has been entered, but no language code. Translations require both the slug and code.");
        }

        if (!$this->service->get_item_by_slug($data->get_english_slug())) {
            throw new PWP_Invalid_Input_Exception("invalid English slug {$data->get_english_slug()} has been passed.");
        }

        $term = $this->create_new_item($data);
        if (!is_null($data->get_seo_data())) {
            $this->service->set_seo_data($term, $data->get_seo_data());
        }

        $Englishparent =  $this->service->get_item_by_slug($data->get_english_slug());
        $this->service->set_translation_data($term, $Englishparent, $data->get_language_code());

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

    public function get_item(int $id, array $args = []): ?WP_Term
    {
        return $this->service->get_item_by_id($id, $args);
    }

    public function get_items(array $args = []): array
    {
        return $this->service->get_items($args);
    }

    public function get_item_by_slug(string $slug, array $args = []): ?WP_Term
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
        $targetTerm = $this->service->get_item_by_slug($slug);
        $data = new PWP_Term_Data($updateData);

        if (!empty($targetTerm->parent)) {
            $data->set_parent_id($targetTerm->parent);
        } else {
            $parentId = $this->get_item_by_slug($data->get_parent_slug())->term_id;
            $data->set_parent_id($parentId ?: 0);
        }

        return $this->service->update_item($targetTerm, $data->to_array());
    }

    /**
     * combined utility of the update and create functions. Will try to update an item first. If it cannot find an item with a matching slug, it will instead create a new item.
     *
     * @param string $slug
     * @param array $data
     * @param array $args
     * @param boolean $useNullValues
     * @return \WP_Term
     */
    final public function update_or_create_item(string $slug, array $data, array $args, bool $useNullValues = false): \WP_Term
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
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

    public function does_slug_exist(string $slug): bool
    {
        return !is_null($this->get_item_by_slug($slug));
    }
}
