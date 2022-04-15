<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Error;
use WP_Term;

abstract class PWP_Term_Handler implements PWP_IHandler
{
    private string $taxonomy;
    private string $longTypeName;

    public function __construct(string $taxonomy, string $typeLongName)
    {
        $this->taxonomy = $taxonomy;
        $this->longTypeName = $typeLongName;
    }

    public function get_item(int $id, array $args = []): ?WP_Term
    {
        $term = get_term($id, $this->taxonomy, OBJECT);
        return $term;
    }

    public function get_items(array $args = []): array
    {
        $terms = get_terms($this->taxonomy);
        return $terms;
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

    protected function create_new(string $name, string $slug, string $description = '', string $parent = '', array $args = []): array
    {
        $term = $this->get_item_by_slug($slug);
        if ($term) {
            return wp_update_term($term->term_id, $this->taxonomy, array(
                'name' => $name,
                'description' => $description,
            ));
        }

        return wp_insert_term($name, $this->taxonomy, array(
            'slug' => $slug,
            'description' => $description,
        ));

        // if (isset($args['seoData'])) {
        //     $this->update_or_add_label_seo_data($result['term_taxonomy_id'], $args['seoData']);
        // }

        // return $result;
    }

    final public function get_item_by_slug(string $slug): ?\WP_Term
    {
        $result = get_term_by('slug', $slug, $this->taxonomy, object);
        return !$result ? $result : null;
    }

    final public function get_item_by_name(string $name): ?\WP_Term
    {
        $result = get_term_by('name', $name, $this->taxonomy, object);
        return !$result ? $result : null;
    }

    final private function update_or_add_label_seo_data($objectId, $seoData)
    {
        $currentSeoMetaData = get_option('wpseo_taxonomy_meta');

        $currentSeoMetaData[$this->type][$objectId]['wpseo_focuskw'] = $seoData->focus_keyword;
        $currentSeoMetaData[$this->type][$objectId]['wpseo_desc'] = $seoData->description;

        update_option('wpseo_taxonomy_meta', $currentSeoMetaData);
    }
}
