<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Error;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\handlers\services\PWP_Term_SVC;
use WP_Term;

include_once(ABSPATH . '/wp-admin/includes/plugin.php');

abstract class PWP_Term_Handler implements PWP_I_Handler
{
    protected PWP_Term_SVC $service;

    public function __construct(PWP_Term_SVC $service)
    {
        $this->service = $service;
    }

    public function create_item(string $identifier, array $args = []): \WP_Term
    {
        $slug = $args['slug'] ?: $this->generate_slug($identifier, $args['language_code']);
        if ($this->service->get_item_by_slug($slug)) {
            throw new \Exception("{$this->beautyName} with the slug {$slug} already exists", 404);
        }

        $englishSlug = $args['english_slug'];
        $langCode = $args['language_code'];

        if (isset($englishSlug)) {
            if (!isset($langCode)) {
                throw new \Exception("English slug has been entered, but no language code. Translations require both the slug and code.");
            }

            if (!$this->service->get_item_by_slug($englishSlug)) {
                throw new \Exception("invalid English slug {$englishSlug} has been passed.");
            }

            //create translated term
            $term = $this->create_new_item($identifier, $slug, $args['description'] ?: '', (int)$args['parent_id'], $args['parent_slug']);
            $this->service->set_seo_data($term, $args['seo']['focus_keyword'], $args['seo']['description']);

            $this->service->set_translation_data($term, $this->service->get_item_by_slug($englishSlug), $langCode);

            return $term;
        }

        //create regular term.

        $term = $this->create_new_item($identifier, $slug, $args['description'] ?: '', (int)$args['parent_id'], $args['parent_slug']);
        $this->service->set_seo_data($term, $args['seo']['focus_keyword'], $args['seo']['description']);

        return $term;
    }

    private function create_new_item(string $identifier, string $slug, string $description = '', ?int $parentId, ?string $parentSlug): \WP_Term
    {
        $parent = $this->find_parent($parentId, $parentSlug);
        $parentId = $parent ? $parent->term_id : 0;

        return $this->service->create_item($identifier, $slug, $description, $parentId);
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

    private function find_parent(?int $id, ?string $slug): ?\WP_Term
    {
        if (!empty($id)) {
            $parent = $this->service->get_item_by_id($id);
            if (!empty($parent)) {
                return $parent;
            }
        }

        if (!empty($slug)) {

            $parent = $this->service->get_item_by_slug($slug);
            if (!empty($parent)) {
                return $parent;
            }
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
