<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use stdClass;
use WP_Error;
use PWP\includes\PWP_ArgBuilder;
use Requests_Exception_HTTP_404;
use WP_HTTP_Response;

abstract class PWP_Term_Handler implements PWP_IHandler
{
    private string $taxonomy;
    private string $longTypeName;

    public function __construct(string $taxonomy, string $typeLongName)
    {
        $this->taxonomy = $taxonomy;
        $this->longTypeName = $typeLongName;
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
