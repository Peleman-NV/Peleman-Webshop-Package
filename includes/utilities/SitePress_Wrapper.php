<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use SitePress;

class SitePress_Wrapper

{
    public ?SitePress $sitepress;
    private bool $sitepressOverrideActive;
    public function __construct()
    {
        if (class_exists('SitePress')) {
            global $sitepress;
            $this->sitepress = $sitepress;
        } else {
            $this->sitepress = null;
        }
        $this->sitepressOverrideActive = false;
    }
    final public function get_sitepress(): ?SitePress
    {
        return $this->sitepress;
    }

    /**
     * disable sitepress filter that adjusts taxonomy ids automatically when calling get_term
     * more information at : https://stackoverflow.com/questions/70789572/wp-term-query-with-wpml-translated-custom-taxonomy
     * @return void
     */
    final public function disable_sitepress_get_term_filter(): void
    {
        if ($this->sitepressOverrideActive) {
            return;
        }

        remove_filter("get_term", array($this->sitepress, 'get_term_adjust_id'), 1);
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
        if (!$this->sitepressOverrideActive) {
            return;
        }

        add_filter("get_term", array($this->sitepress, 'get_term_adjust_id'), 1, 1);
        add_filter("get_terms_args", array($this->sitepress, "get_terms_args_filter"), 10, 2);
        add_filter("terms_clauses", array($this->sitepress, "terms_clauses"), 10, 3);

        $this->sitepressOverrideActive = false;
    }

    final public function get_active_languages(bool $refresh = false, bool $majorFirst = false): array
    {
        if ($this->sitepress) {
            $langs = $this->sitepress->get_active_languages($refresh, $majorFirst);
            return array_keys($langs);
        }
        return get_available_languages();
    }
}
