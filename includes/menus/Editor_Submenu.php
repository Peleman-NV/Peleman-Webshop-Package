<?php

declare(strict_types=1);

namespace PWP\includes\menus;

use PWP\adminPage\hookables\Admin_Control_Panel;

class Editor_Submenu extends Admin_menu
{
    public function render_menu(): void
    {
        $this->register_settings();
        $this->add_menu_components();
    }

    private function register_settings(): void
    {
        register_setting('pwp-editor-options-group', 'pie_domain', array(
            'type' => 'string',
            'description' => 'base Site Address of the PIE editor',
            'sanitize_callback' => 'esc_url_raw',
            'show_in_rest' => false,
            'default' => ''
        ));
        register_setting('pwp-editor-options-group', 'pie_customer_id', array(
            'type' => 'string',
            'description' => 'customer id for the PIE Editor',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => false,
            'default' => ''
        ));
        register_setting('pwp-editor-options-group', 'pie_api_key', array(
            'type' => 'string',
            'description' => 'customer api key for PIE Editor',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => false,
            'default' => ''
        ));
    }

    private function add_menu_components(): void
    {
        add_settings_section(
            'pwp_settings_editors',
            __("Editor", PWP_TEXT_DOMAIN),
            null,
            Admin_Control_Panel::PAGE_SLUG,
        );
        add_settings_field(
            'pie_domain',
            __("PIE domain (URL)", PWP_TEXT_DOMAIN),
            array($this, 'text_property_callback'),
            Admin_Control_Panel::PAGE_SLUG,
            "pwp_settings_editors",
            array(
                'option' => 'pie_domain',
                'placeholder' => "https://deveditor.peleman.com",
                'description' => __("base Site Address of the PIE editor", PWP_TEXT_DOMAIN)
            )
        );
        add_settings_field(
            'pie_customer_id',
            __("PIE domain (URL)", PWP_TEXT_DOMAIN),
            array($this, 'text_property_callback'),
            Admin_Control_Panel::PAGE_SLUG,
            "pwp_settings_editors",
            array(
                'option' => 'pie_customer_id',
            )
        );
        add_settings_field(
            'pie_api_key',
            __("PIE domain (URL)", PWP_TEXT_DOMAIN),
            array($this, 'text_property_callback'),
            Admin_Control_Panel::PAGE_SLUG,
            "pwp_settings_editors",
            array(
                'option' => 'pie_api_key',
            )
        );
    }
}
