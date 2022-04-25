<?php

declare(strict_types=1);

namespace PWP\includes\handlers\Items;

use PWP\includes\utilities\PWP_WPDB;

abstract class PWP_Term implements PWP_I_Item
{
    private string $taxonomy;
    private string $beautyName;
    private string $elementName;
    private array $data;

    public function __construct(string $taxonomy, string $beautyName, string $elementName)
    {
        $this->taxonomy = $taxonomy;
        $this->beautyName = $beautyName;
        $this->elementName = $elementName;
    }

    public function create_item(string $name, string $slug = '', ?string $description, ?int $parentId)
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

    final public function get_taxonomy(): string
    {
        return $this->taxonomy;
    }

    final public function set_seo_data(\WP_Term $term, string $focusKeyword, string $description): void
    {
        if (!isset($seoData)) return;

        $currentSeoMetaData = get_option('wpseo_taxonomy_meta');

        $currentSeoMetaData[$this->taxonomy][$term->id]['wpseo_focuskw'] = $seoData[] = $focusKeyword;
        $currentSeoMetaData[$this->taxonomy][$$term->id]['wpseo_desc'] = $seoData['description'] = $description;

        update_option('wpseo_taxonomy_meta', $currentSeoMetaData);
    }

    final public function set_translation_data(\WP_Term $translatedTerm, \WP_Term $originalTerm, string $lang): void
    {
        if (!class_exists('SitePress')) return;

        $taxonomyId = $translatedTerm->term_taxonomy_id;
        $wpdb = PWP_WPDB::get_wpdb();

        $table = $wpdb->prefix . 'icl_translations';

        $sitepress = new \SitePress();
        $trid = $sitepress->get_element_trid($originalTerm->term_id, $this->elementType);

        $query = $wpdb->prepare(
            "UPDATE {$table} SET language_code = '%s', source_language_code = 'en', trid = %d WHERE element_type = '%s' AND element_id = %d;",
            $lang,
            $trid,
            $this->elementType,
            $taxonomyId
        );
        $wpdb->query($query);

        return;
    }

    final public function delete_item(int $id, array $args = []): bool
    {
        $result = wp_delete_term($id, $this->taxonomy, $args);
        if ($result === true) return true;
        return false;
    }
}
