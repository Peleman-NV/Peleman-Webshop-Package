<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\utilities\PWP_ILogger;
use WP_Error;
use WP_Term;

abstract class PWP_Term_Handler implements PWP_IHandler
{
    protected PWP_ILogger $logger;
    private string $taxonomy;
    private string $longTypeName;

    public function __construct(string $taxonomy, string $typeLongName, PWP_ILogger $logger)
    {
        $this->logger = $logger;
        $this->taxonomy = $taxonomy;
        $this->longTypeName = $typeLongName;
    }

    public function create_item(string $identifier, array $args = []): object
    {
        $parent = $this->find_parent((int)$args['parent-id'], $args['parent-slug']);

        $slug = $args['slug'];
        if (!isset($slug)) {
            $slug = strtolower($identifier);
            $slug = str_replace(' ', '_', $slug);
        }

        $term = $this->get_item_by_slug($slug);

        if ($term) {
            throw new \Exception("tag with this slug already exists in the database", 404);
        }

        $result =  wp_insert_term($identifier, $this->taxonomy, array(
            'slug' => $slug,
            'description' => $args['description'],
            'parent' => $parent->term_id ?: 0,
        ));

        // if (isset($args['seoData'])) {
        //     $this->update_or_add_label_seo_data($result['term_taxonomy_id'], $args['seoData']);
        // }

        return get_term($result['term_id']);
    }

    public function get_item(int $id, array $args = []): ?\WP_Term
    {
        $term = get_term($id, $this->taxonomy, OBJECT);
        if ($term instanceof \WP_Term) {
            return $term;
        }
        return null;
    }

    public function get_items(array $args = []): array
    {
        $args['taxonomy'] = $this->get_taxonomy();
        $terms = get_terms($args);
        return $terms;
    }

    public function update_item(int $id, array $args = []): object
    {
        return new \WP_Term(0);
    }

    public function delete_item(int $id, array $args = []): bool
    {
        $outcome = wp_delete_term($id, $this->taxonomy, $args);
        if ($outcome instanceof WP_Error || !$outcome) {
            throw new \Exception("{$this->longTypeName} not found", 404);
            return false;
        }
        if ($outcome === 0) {
            throw new \Exception("attempted to delete default category", 400);
            return false;
        }

        return true;
    }

    public function delete_item_by_slug(string $slug, array $args = []): bool
    {
        $term = $this->get_item_by_slug($slug);
        if (empty($term)) throw new \Exception("term with slug {$slug} not found", 404);

        return $this->delete_item($term->term_id, $args);
    }

    final public function get_item_by_slug(string $slug): ?\WP_Term
    {
        $result = get_term_by('slug', $slug, $this->taxonomy);
        return !empty($result) ? $result : null;
    }

    final public function get_item_by_name(string $name): ?\WP_Term
    {
        $result = get_term_by('name', $name, $this->taxonomy);
        return !$result ? $result : null;
    }

    final private function update_or_add_label_seo_data($objectId, $seoData)
    {
        $currentSeoMetaData = get_option('wpseo_taxonomy_meta');

        $currentSeoMetaData[$this->taxonomy][$objectId]['wpseo_focuskw'] = $seoData->focus_keyword;
        $currentSeoMetaData[$this->taxonomy][$objectId]['wpseo_desc'] = $seoData->description;

        update_option('wpseo_taxonomy_meta', $currentSeoMetaData);
    }

    public function get_taxonomy(): string
    {
        return $this->taxonomy;
    }

    private function find_parent(?int $id, ?string $slug): ?WP_Term
    {
        if (is_null($id) && is_null($slug)) return null;

        if (!empty($id) || 0 >= $id) {
            $parent = $this->get_item($id);
            if (!empty($parent)) {
                return $parent;
            }
        }

        if (!empty($slug)) {
            $parent = $this->get_item_by_slug($slug);
            if (!empty($parent)) {
                return $parent;
            }
        }

        throw new \Exception("Parent not found!", 404);
    }
}
