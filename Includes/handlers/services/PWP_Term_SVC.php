<?php

declare(strict_types=1);

namespace PWP\includes\handlers\services;

use PWP\includes\utilities\PWP_WPDB;
use PWP\includes\wrappers\PWP_SEO_Data;
use PWP\includes\handlers\services\PWP_I_SVC;
use PWP\includes\exceptions\PWP_WP_Error_Exception;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;

class PWP_Term_SVC implements PWP_I_SVC
{
    private string $taxonomy;
    private string $elementType;
    private string $beautyName;

    private string $sourceLang;

    /**
     * @param string $taxonomy taxonomy of the term
     * @param string $elementType name of the element for use with WPML translations. 
     * @param string $beautyName beautified name for use in human readable errors.
     * @param string $sourceLang 2 letter lower-case language code. default is en (English)
     */
    public function __construct(string $taxonomy, string $elementType, string $beautyName, string $sourceLang = 'en')
    {
        $this->taxonomy = $taxonomy;
        $this->elementType = $elementType;
        $this->beautyName = $beautyName;

        $this->sourceLang = $sourceLang;
    }

    final public function create_item(string $name, string $slug, string $description = '', int $parentId = 0)
    {
        $termData =  wp_insert_term($name, $this->taxonomy, array(
            'slug' => $slug,
            'description' => $description,
            'parent' => $parentId
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
     * @param array $args
     * @return \WP_Term
     */
    final public function update_item(\WP_Term $term, array $args = []): \WP_Term
    {
        $termData = wp_update_term($term->term_id, $term->taxonomy, $args);
        if ($termData instanceof \WP_Error) {
            throw new \Exception($termData->get_error_message(), $termData->get_error_code());
        }

        //get fresh version of the term
        return $this->get_item_by_id($term->term_id);
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

    final public function get_item_by_id(int $id, string $lang = 'en'): ?\WP_Term
    {
        $termData = get_term_by('id', $id, $this->taxonomy,);
        if (!$termData) {
            return null;
        }
        return $termData;
    }

    final public function get_item_by_name(string $name): ?\WP_Term
    {

        $termData = get_term_by('name', $name, $this->taxonomy);
        if (!$termData) {
            var_dump($termData);
            return null;
        }
        return $termData;
    }

    final public function get_item_by_slug(string $slug): ?\WP_Term
    {
        $termData = get_term_by('slug', $slug, $this->taxonomy);
        var_dump($termData);
        if (!$termData) {
            return null;
        }
        return $termData;
    }

    final public function set_seo_data(\WP_Term $term, PWP_SEO_Data $data): void
    {
        // if (!isset($seoData)) return;

        $currentSeoMetaData = get_option('wpseo_taxonomy_meta');

        $currentSeoMetaData[$this->taxonomy][$term->id]['wpseo_focuskw'] = $data->get_focus_keyword();
        $currentSeoMetaData[$this->taxonomy][$$term->id]['wpseo_desc'] = $data->get_description();

        update_option('wpseo_taxonomy_meta', $currentSeoMetaData);
    }

    final public function set_translation_data(\WP_Term $translatedTerm, \WP_Term $originalTerm, string $lang, ?string $sourceLang = null): bool
    {
        if (!class_exists('SitePress')) return false;

        $sitepress = new \SitePress();
        $wpdb = new PWP_WPDB();

        $taxonomyId = $translatedTerm->term_taxonomy_id;
        $trid = $sitepress->get_element_trid($originalTerm->term_taxonomy_id, $this->elementType);

        $query = $wpdb->prepare_term_translation_query($lang, $sourceLang, (int)$trid, $this->elementType, $taxonomyId);
        var_dump($query);
        $result = $wpdb->query($query);

        return !$result;
    }

    final public function delete_item(\WP_Term $term): bool
    {
        $result = wp_delete_term($term->term_id, $this->taxonomy);
        if ($result === true) return true;

        if ($result instanceof \WP_Error) {
            throw new PWP_WP_Error_Exception($result);
        }
        if ($result === 0) {
            throw new PWP_Invalid_Input_Exception("tried to delete {$this->beautyName}, which is a default category and not allowed.");
        }

        return false;
    }

    public function does_slug_exist(string $slug): bool
    {
        return !is_null($this->service->get_item_by_slug($slug));
    }

    final public function get_beauty_name(): string
    {
        return $this->beautyName;
    }

    final public function get_taxonomy(): string
    {
        return $this->taxonomy;
    }

    final public function get_taxonomy_type(): string
    {

        return $this->elementType;
    }
}
