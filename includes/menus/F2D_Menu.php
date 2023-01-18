<?php

declare(strict_types=1);

namespace PWP\includes\menus;

use PWP\adminPage\hookables\Admin_Control_Panel;
use PWP\includes\menus\Admin_Menu;

class F2D_Menu extends Admin_Menu
{
    public function render_menu(): void
    {
        $this->register_settings();
        $this->add_menu_components();
    }

    protected function register_settings(): void
    {
        register_setting('pwp-f2d-options-group', 'pwp_enable_f2d', array(
            'type' => 'bool',
            'description' => 'Enable F2D integration',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => true,
            'default' => false,
        ));
    }
    protected function add_menu_components(): void
    {
        add_settings_section(
            'pwp_settings_f2d',
            __("Fly2Data", PWP_TEXT_DOMAIN),
            null,
            Admin_Control_Panel::PAGE_SLUG,
        );
        add_settings_field(
            'pwp_enable_f2d',
            __("Enable F2D integration", PWP_TEXT_DOMAIN),
            array($this, 'bool_property_callback'),
            Admin_Control_Panel::PAGE_SLUG,
            "pwp_settings_f2d",
            array(
                'option' => 'pwp_enable_f2d',
                'description' =>  __("Enables F2D specific properties on products and API. ", PWP_TEXT_DOMAIN),
            )
        );
    }
}
