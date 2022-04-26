<?php

declare(strict_types=1);

namespace PWP\includes\handlers\services;

use PWP\includes\utilities\PWP_WPDB;
use PWP\includes\handlers\services\PWP_I_SVC;

abstract class PWP_Term_SVC implements PWP_I_SVC
{
    private string $taxonomy;
    private string $beautyName;
    private string $elementType;
    private string $sourceLang;

    /**
     * Undocumented function
     *
     * @param string $taxonomy taxonomy of the term
     * @param string $beautyName beautified name for use in human readable errors.
     * @param string $elementType name of the element for use with WPML translations. 
     * @param string $sourceLang 2 letter lower-case language code. default is en (English)
     */
    public function __construct(string $taxonomy, string $elementType, string $beautyName, string $sourceLang = 'en')
    {
        $this->taxonomy = $taxonomy;
        $this->beautyName = $beautyName;
        $this->elementType = $elementType;
        $this->sourceLang = $sourceLang;
    }

    final public function create_item(string $name, string $slug = '', ?string $description, ?int $parentId)
    {
        $termData =  wp_insert_term($name, $this->taxonomy, array(
            'slug' => $slug,
            'description' => $description ?: '',
            'parent' => $parentId ?: 0
        ));

        if ($termData instanceof \WP_Error) {
            throw new \Exception($termData->get_error_message(), $termData->get_error_code());
        }

        return $this->get_item_by_id($termData['term_id']);
    }

    final public function get_name(): string
    {
        return $this->beautyName;
    }

    /**
     * Undocumented function
     *
     * @param \WP_Term $term
     * @param boolean $useNullValues if true, arguments that are null or empty will be persisted. 
     * if false, they will be ignored and the original value left in its place. default false.
     * @param array $args
     * @return \WP_Term
     */
    final public function update_item(\WP_Term $term, array $args = [], bool $useNullValues = false): \WP_Term
    {
        if (!$useNullValues) {
            $args = $this->filter_null_values_from_array($args);
        }

        wp_update_term($term->id, $term->taxonomy, $args);
        return $term;
    }

    /**
     * retrieve all items of type `WP_Term`. use $args to handle settings of this function
     *
     * @param array $args
     * @return \WP_Term[]
     */
    final public function get_items(array $args = []): array
    {
        $args['taxonomy'] = $this->taxonomy;
        $args['hide_empty'] = false;
        return get_terms($args);
    }

    final public function get_item_by_id(int $id): ?\WP_Term
    {
        $termData = get_term_by('id', $id, $this->taxonomy,);
        return $termData ?: null;
    }

    final public function get_item_by_name(string $name): ?\WP_Term
    {
        $termData = get_term_by('name', $name, $this->taxonomy);
        return $termData ?: null;
    }

    final public function get_item_by_slug(string $slug): ?\WP_Term
    {
        $termData = get_term_by('slug', $slug, $this->taxonomy);
        return $termData ?: null;
    }

    final public function set_seo_data(\WP_Term $term, string $focusKeyword, string $description): void
    {
        if (!isset($seoData)) return;

        $currentSeoMetaData = get_option('wpseo_taxonomy_meta');

        $currentSeoMetaData[$this->taxonomy][$term->id]['wpseo_focuskw'] = $seoData[] = $focusKeyword;
        $currentSeoMetaData[$this->taxonomy][$$term->id]['wpseo_desc'] = $seoData['description'] = $description;

        update_option('wpseo_taxonomy_meta', $currentSeoMetaData);
    }

    final public function set_translation_data(\WP_Term $translatedTerm, \WP_Term $originalTerm, string $lang): bool
    {
        if (!class_exists('SitePress')) return false;

        $sitepress = new \SitePress();
        $wpdb = new PWP_WPDB();

        $taxonomyId = $translatedTerm->term_taxonomy_id;
        $trid = $sitepress->get_element_trid($originalTerm->term_id, $this->elementType);

        $result = $wpdb->query($wpdb->prepare_term_translation_query($lang, $this->sourceLang, (int)$trid, $this->elementType, $taxonomyId));

        return !$result;
    }

    final public function delete_item(int $id, array $args = []): bool
    {
        $result = wp_delete_term($id, $this->taxonomy, $args);
        if ($result === true) return true;
        return false;
    }

    final private function filter_null_values_from_array(array $array): array
    {
        return array_filter($array, function ($entry) {
            return !($entry === null || $entry === '' || $entry === [] || isset($entry));
        });
    }
}
