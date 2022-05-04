<?php

declare(strict_types=1);

namespace PWP\includes\handlers\services;

use PWP\includes\utilities\PWP_WPDB;
use PWP\includes\wrappers\PWP_SEO_Data;
use PWP\includes\handlers\services\PWP_I_SVC;
use PWP\includes\exceptions\PWP_WP_Error_Exception;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use SitePress;
use WP_Term;

class PWP_Term_SVC implements PWP_I_SVC
{
    private string $taxonomy;
    private string $elementType;
    private string $beautyName;

    private string $sourceLang;
    private ?SitePress $sitepress;
    private bool $sitepressOverrideActive;

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
        $this->sitepress = $this->get_sitepress();
        $this->sitepressOverrideActive = false;
    }

    final public function create_item(string $name, string $slug, string $description = '', int $parentId = 0)
    {
        $termData =  wp_insert_term($name, $this->taxonomy, array(
            'slug' => $slug,
            'description' => $description,
            'parent' => $parentId
        ));

        if ($termData instanceof \WP_Error) {
            wp_die($termData);
        }

        return $this->get_item_by_id($termData['term_id']);
    }

    final public function get_name(): string
    {
        return $this->beautyName;
    }

    final public function update_item(WP_Term $term, string $taxonomy, array $args = []): WP_Term
    {
        $termData = wp_update_term($term->term_id, $taxonomy, $args);
        if (is_wp_error($termData)) {
            // wp_die(sprintf(
            //     "encountered fatal error in %s:%s line %d. unsuccessful term update on slug %s",
            //     __file__,
            //     __METHOD__,
            //     __LINE__,
            //     $term->slug
            // ));
            wp_die($termData);
        }

        //get fresh version of the term
        return $this->get_item_by_id($term->term_id);
    }

    /**
     * retrieve all items of type `WP_Term`. use $args to handle settings of this function
     *
     * @param array $args
     * @return WP_Term[]
     */
    final public function get_items(array $args = []): array
    {
        $args['taxonomy'] = $this->taxonomy;
        $args['hide_empty'] = false;
        return get_terms($args);
    }

    /**
     * wrapper function for get_term_by function.
     * 
     * wraps around `get_term_by` method. if Sitepress/WPML is active, will work with its logic.
     * this can be disabled by calling this class's `disable_sitepress_get_term_filter` method before calling this method.
     *
     * @param integer $id
     * @return WP_Term|null
     */
    final public function get_item_by_id(int $id): ?WP_Term
    {
        $termData = get_term_by('id', $id, $this->taxonomy,);
        if (!$termData) {
            return null;
        }
        return $termData;
    }

    /**
     * wrapper function for get_term_by function.
     * 
     * wraps around `get_term_by` method. if Sitepress/WPML is active, will work with its logic.
     * this can be disabled by calling this class's `disable_sitepress_get_term_filter` method before calling this method.
     *
     * @param string $name
     * @return WP_Term|null
     */
    final public function get_item_by_name(string $name): ?WP_Term
    {
        $termData = get_term_by('name', $name, $this->taxonomy);
        if (!$termData) {
            return null;
        }
        return $termData;
    }

    /**
     * wrapper function for get_term_by function.
     * 
     * wraps around `get_term_by` method. if Sitepress/WPML is active, will work with its logic.
     * this can be disabled by calling this class's `disable_sitepress_get_term_filter` method before calling this method.
     *
     * @param string $slug
     * @return WP_Term|null
     */
    final public function get_item_by_slug(string $slug): ?WP_Term
    {
        $termData = get_term_by('slug', $slug, $this->taxonomy);

        if (!$termData) {
            return null;
        }
        return $termData;
    }

    final public function get_original_translation_id(WP_Term $term): int
    {
        if (is_null($this->sitepress)) {
            return -1;
        }
        return $this->sitepress->get_object_id($term->term_id, $this->elementType, false, $this->sourceLang);
    }

    final public function set_seo_data(WP_Term $term, PWP_SEO_Data $data): void
    {
        // if (!isset($seoData)) return;

        $currentSeoMetaData = get_option('wpseo_taxonomy_meta');

        $currentSeoMetaData[$this->taxonomy][$term->id]['wpseo_focuskw'] = $data->get_focus_keyword();
        $currentSeoMetaData[$this->taxonomy][$$term->id]['wpseo_desc'] = $data->get_description();

        update_option('wpseo_taxonomy_meta', $currentSeoMetaData);
    }

    final public function configure_translation(WP_Term $translatedTerm, WP_Term $originalTerm, string $lang): bool
    {
        if (is_null($this->sitepress)) {
            return false;
        }

        $wpdb = new PWP_WPDB();

        $taxonomyId = $translatedTerm->term_taxonomy_id;
        $parentTaxonomyId = $originalTerm->term_taxonomy_id;
        $trid = $this->sitepress->get_element_trid($parentTaxonomyId, $this->elementType);

        $sourceLang = $this->sourceLang !== $lang ? $this->sourceLang : null;

        $query = $wpdb->prepare_term_translation_query($lang, $sourceLang, (int)$trid, $this->elementType, $taxonomyId);
        $result = $wpdb->query($query);

        return !$result;
    }

    final public function delete_item(WP_Term $term): bool
    {
        $result = wp_delete_term($term->term_id, $this->taxonomy);
        if ($result === true) return true;

        if ($result instanceof \WP_Error) {
            throw new PWP_WP_Error_Exception($result);
        }
        if ($result === 0) {
            throw new PWP_Invalid_Input_Exception("tried to delete {$this->beautyName} {$term->name}, which is a default category and not allowed.");
        }

        return false;
    }

    public function is_slug_in_use(string $slug): bool
    {
        return !is_null($this->get_item_by_slug($slug));
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

    final public function get_sourcelang(): ?string
    {
        return $this->sourceLang;
    }

    final public function get_translation_id(WP_Term $original, string $lang): int
    {
        if (is_null($this->sitepress)) {
            return -1;
        }

        $trid = $this->get_trid($original);
        if (0 >= $trid) {
            return 0;
        }

        $translations = $this->sitepress->get_element_translations((int)$trid, $this->elementType, false, false);
        return (int)$translations[$lang]->element_id;
    }

    final public function get_trid(WP_Term $original): int
    {
        if (is_null($this->sitepress)) {
            return -1;
        }
        $trid = $this->sitepress->get_element_trid($original->term_id, $this->elementType);
        var_dump($trid);
        return (int)$trid ?: 0;
    }

    /**
     * disable sitepress filter that adjusts taxonomy ids automatically when calling get_term
     * more information at : https://stackoverflow.com/questions/70789572/wp-term-query-with-wpml-translated-custom-taxonomy
     * @return void
     */
    final public function disable_sitepress_get_term_filter(): void
    {
        if (!isset($this->sitepress) || $this->sitepressOverrideActive) {
            return;
        }

        remove_filter("get_term", array($this->sitepress, 'get_term_adjust_id'), 1, 1);
        remove_filter("get_terms_args", array($this->sitepress, "get_terms_args_filter"), 10);
        remove_filter("terms_clauses", array($this->sitepress, "terms_clauses"), 10);

        $this->sitepressOverrideActive = true;
    }

    /**
     * enable sitepress filter that adjusts taxonomy ids automatically when calling get_term
     * more information at : https://stackoverflow.com/questions/70789572/wp-term-query-with-wpml-translated-custom-taxonomy
     * @return void
     */
    final public function enable_sitepress_get_term_filter(): void
    {
        if (!isset($this->sitepress) || !$this->sitepressOverrideActive) {
            return;
        }

        add_filter("get_term", array($this->sitepress, 'get_term_adjust_id'), 1, 1);
        add_filter("get_terms_args", array($this->sitepress, "get_terms_args_filter"), 10);
        add_filter("terms_clauses", array($this->sitepress, "terms_clauses"), 10);

        $this->sitepressOverrideActive = false;
    }

    final public function get_sitepress(): ?SitePress
    {
        if (!class_exists('SitePress')) {
            return null;
        }
        global $sitepress;
        return $sitepress;
    }
}
