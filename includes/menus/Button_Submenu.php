<?php

declare(strict_types=1);

namespace PWP\includes\menus;

use PWP\adminPage\hookables\Admin_Control_Panel;

class Button_Submenu extends Admin_Menu
{
    public function __construct()
    {
        parent::__construct('Buttons', 'pwp-button-options-group');
    }

    public function register_settings(): void
    {
        register_setting($this->option_group, 'pwp_customize_label', array(
            'type' => 'string',
            'description' => 'label for shop archive; to be displayed on products that require user customization/uploads',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => true,
            'default' => 'Customize product',
        ));
        register_setting($this->option_group, 'pwp_archive_var_label', array(
            'type' => 'string',
            'description' => 'label for shop archive; to be displayed on variable products that require user customization/uploads',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => true,
            'default' => 'Choose Options',
        ));
        register_setting($this->option_group, 'pwp_project_cleanup_cutoff_days', array(
            'type' => 'string',
            'description' => 'amount of days before an uploaded pdf project is removed. Only pdfs which have not been ordered will be deleted.',
            'sanitize_callback' => 'sanitize_key',
            'show_in_rest' => false,
            'default' => 15,
        ));

        $this->add_menu_components();
    }

    public function add_menu_components(): void
    {
        add_settings_section(
            'pwp_settings_buttons',
            __("Buttons", 'Peleman-Webshop-Package'),
            array($this, ''),
            Admin_Control_Panel::PAGE_SLUG,
        );
        add_settings_field(
            'pwp_customize_label',
            __("Simple product - customizable", 'Peleman-Webshop-Package'),
            array($this, 'text_property_callback'),
            Admin_Control_Panel::PAGE_SLUG,
            "pwp_settings_buttons",
            array(
                'option' => 'pwp_customize_label',
                'placeholder' => 'customize me',
                'description' =>  __("label for products that require customization/user input", 'Peleman-Webshop-Package'),
            )
        );
        add_settings_field(
            'pwp_archive_var_label',
            __("Variable product - customizable", 'Peleman-Webshop-Package'),
            array($this, 'text_property_callback'),
            Admin_Control_Panel::PAGE_SLUG,
            "pwp_settings_buttons",
            array(
                'option' => 'pwp_archive_var_label',
                'placeholder' => 'customize me',
                'description' =>  __("label for customizable variable products", 'Peleman-Webshop-Package')
            )
        );
    }
}
