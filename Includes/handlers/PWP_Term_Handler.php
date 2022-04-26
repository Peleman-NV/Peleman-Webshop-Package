<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Error;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\handlers\Items\PWP_I_SVC;
use PWP\includes\handlers\Items\PWP_Term_SVC;
use WP_Term;

include_once(ABSPATH . '/wp-admin/includes/plugin.php');

abstract class PWP_Term_Handler implements PWP_I_Handler
{
    private PWP_Term_SVC $service;

    public function __construct(PWP_I_SVC $service)
    {
        $this->service = $service;
    }

    public function create_item(string $identifier, array $args = []): \WP_Term
    {
        $slug = $args['slug'] ?: $this->generate_slug($identifier, $args['language_code']);

        if ($this->service->get_item_by_slug($slug)) {
            throw new \Exception("{$this->beautyName} with the slug {$slug} already exists", 404);
        }

        $parent = $this->find_parent((int)$args['parent_id'], $args['parent_slug']);
        $parentId = $parent ? $parent->term_id : 0;

        $term = $this->service->create_item($identifier, $slug, $args['description'], $parentId);

        $this->service->set_seo_data($term, $args['seo']['focus_keyword'], $args['seo']['description']);
        $this->service->set_translation_data($term, $this->service->get_item_by_slug($args['english_slug']), $args['language_code']);

        return $term;
    }

    public function get_item(int $id, array $args = []): ?\WP_Term
    {
        return $this->service->get_item_by_id($id);
    }

    public function get_items(array $args = []): array
    {
        return $this->service->get_items($args);
    }

    public function update_item(int $id, array $args = [], bool $useNullValues = false): WP_Term
    {
        throw new PWP_Not_Implemented_Exception(sprintf("%s : %s: function %s not implemented!", __FILE__, __LINE__, __METHOD__));
    }

    public function delete_item(int $id, array $args = []): bool
    {
        $outcome = $this->service->delete_item($id, $args);

        if ($outcome === 0) {
            throw new \Exception("attempted to delete default category", 400);
        }

        if ($outcome instanceof WP_Error) {
            return false;
        }
        return $outcome;
    }

    final public function update_item_by_slug(string $slug, array $args = []): ?\WP_TERM
    {
        //TODO: write implementation
        $service = $this->service->get_item_by_slug($slug);
        return is_null($service) ? null : $this->service->update_item($service, $args);
    }

    public function delete_item_by_slug(string $slug, array $args = []): bool
    {
        $term = $this->service->get_item_by_slug($slug);
        if (empty($term)) {
            throw new \Exception("term with slug {$slug} not found", 404);
        }

        return $this->delete_item($term->term_id, $args);
    }

    private function find_parent(int $id = 0, string $slug = ''): ?\WP_Term
    {
        $parent = $this->service->get_item_by_id($id);
        if (!empty($parent)) {
            return $parent;
        }

        $parent = $this->service->get_item_by_slug($slug);
        if (!empty($parent)) {
            return $parent;
        }

        return null;
    }

    private function generate_slug(string $name, ?string $lang = null): string
    {
        $slug = strtolower($name);
        $slug = str_replace(' ', '_', $slug);

        if (!empty($lang)) {
            $slug .= "-{$lang}";
        }
        return $slug;
    }
}
