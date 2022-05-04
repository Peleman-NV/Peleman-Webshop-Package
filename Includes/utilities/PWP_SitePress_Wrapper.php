<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use SitePress;

class PWP_SitePress_Wrapper

{
    public ?SitePress $sitepress;
    public function __construct()
    {
        global $sitepress;
        $this->sitepress = $sitepress;
        $this->sitepressOverrideActive = false;
    }
    final public function get_sitepress(): ?SitePress
    {
        if (!class_exists('SitePress')) {
            return null;
        }
        return $this->sitepress;
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
}
