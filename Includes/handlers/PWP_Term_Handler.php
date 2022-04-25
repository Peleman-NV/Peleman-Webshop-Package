<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Error;
use PWP\includes\handlers\Items\PWP_Term;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

include_once(ABSPATH . '/wp-admin/includes/plugin.php');

abstract class PWP_Term_Handler implements PWP_I_Handler
{
    private PWP_Term $item;

    /**
     * Undocumented function
     *
     * @param string $taxonomy taxonomy of the term that this handler will create
     * @param string $beautyName beauty name to display in error messages
     * @param string $elementType element type of the term; for use with WPML translations.
     * @param PWP_ILogger $logger
     */
    public function __construct(string $taxonomy, string $beautyName, string $elementType)
    {
        $this->item = new PWP_Term($taxonomy, $beautyName, $elementType);
    }

    public function create_item(string $identifier, array $args = []): \WP_Term
    {
        $slug = $args['slug'] ?: $this->generate_slug($identifier, $args['language_code']);

        if ($this->item->get_item_by_slug($slug)) {
            throw new \Exception("{$this->beautyName} with the slug {$slug} already exists", 404);
        }

        $parent = $this->find_parent((int)$args['parent_id'], $args['parent_slug']);
        $parentId = $parent ? $parent->term_id : 0;

        $term = $this->item->create_item($identifier, $slug, $args['description'], $parentId);
        $this->item->set_seo_data($term, $args['seo']['focus_keyword'], $args['seo']['description']);
        $this->item->set_translation_data($term, $this->item->get_item_by_slug($args['english_slug']), $args['language_code']);

        return $term;
    }

    public function get_item(int $id, array $args = []): ?\WP_Term
    {
        return $this->item->get_item_by_id($id);
    }

    public function get_items(array $args = []): array
    {
        $args['taxonomy'] = $this->item->get_taxonomy();
        $terms = get_terms($args);
        return $terms;
    }

    public function update_item(int $id, array $args = []): object
    {
        throw new PWP_Not_Implemented_Exception(sprintf("%s : %s: function %s not implemented!", __FILE__, __LINE__, __METHOD__));
    }

    public function delete_item(int $id, array $args = []): bool
    {
        $outcome = $this->item->delete_item($id, $args);

        if ($outcome instanceof WP_Error || !$outcome) {
            throw new \Exception("was not capable of deleting item. {$this->beautyName} not found", 404);
        }
        if ($outcome === 0) {
            throw new \Exception("attempted to delete default category", 400);
        }

        return $outcome;
    }

    final public function update_item_by_slug(string $slug, array $args = []): ?\WP_TERM
    {
        //TODO: write implementation
        throw new PWP_Not_Implemented_Exception(sprintf("%s : %s: function %s not implemented!", __FILE__, __LINE__, __METHOD__));
        return null;
    }

    public function delete_item_by_slug(string $slug, array $args = []): bool
    {
        $term = $this->item->get_item_by_slug($slug);
        if (empty($term)) {
            throw new \Exception("term with slug {$slug} not found", 404);
        }

        return $this->delete_item($term->term_id, $args);
    }

    private function find_parent(int $id = 0, string $slug = ''): ?\WP_Term
    {
        $parent = $this->item->get_item_by_id($id);
        if (!empty($parent)) {
            return $parent;
        }

        $parent = $this->item->get_item_by_slug($slug);
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
