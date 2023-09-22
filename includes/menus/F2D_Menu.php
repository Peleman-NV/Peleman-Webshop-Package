<?php

declare(strict_types=1);

namespace PWP\includes\menus;

use PWP\adminPage\hookables\Admin_Control_Panel;
use PWP\includes\menus\Admin_Menu;

class F2D_Menu extends Admin_Menu
{
    public function __construct(string $page_slug)
    {
        parent::__construct('F2D options', 'pwp-f2d-options-group', $page_slug);
    }

    public  function register_settings(): void
    {
        register_setting(
            $this->option_group,
            'pwp_settings_f2d',
            array(
                'type' => 'bool',
                'description' => 'Enable F2D integration',
                'sanitize_callback' => 'wp_filter_nohtml_kses',
                'show_in_rest' => true,
                'default' => false,
            )
        );

        register_setting(
            $this->option_group,
            'pwp_update_automatic',
            array(
                'type' => 'bool',
                'description' => 'Enable daily updates through git',
                'sanitize_callback' => 'wp_filter_nohtml_kses',
                'show_in_rest' => true,
                'default' => false,
            )
        );

        register_setting(
            $this->option_group,
            'pwp_git_update_branch',
            array(
                'type' => 'string',
                'description' => 'Git branch to pull from',
                'sanitize_callback' => 'wp_filter_nohtml_kses',
                'show_in_rest' => true,
                'default' => 'main',
            )
        );

        add_settings_section(
            'pwp_settings_f2d',
            __("Fly2Data", 'Peleman-Webshop-Package'),
            null,
            $this->page_slug,
        );
        add_settings_field(
            'pwp_enable_f2d',
            __("Enable F2D integration", 'Peleman-Webshop-Package'),
            array($this, 'bool_property_callback'),
            $this->page_slug,
            "pwp_settings_f2d",
            array(
                'option' => 'pwp_enable_f2d',
                'description' =>  __("Enables F2D specific properties on products and API. ", 'Peleman-Webshop-Package'),
            )
        );

        add_settings_field(
            'pwp_update_automatic',
            __("Enable Git updates", 'Peleman-Webshop-Package'),
            array($this, 'bool_property_callback'),
            $this->page_slug,
            "pwp_update_automatic",
            array(
                'option' => 'pwp_enable_f2d',
                'description' =>  __("Enables cron job that will attempt a daily update of the plugin through a git request", 'Peleman-Webshop-Package'),
            )
        );

        add_settings_field(
            'pwp_git_update_branch',
            __("Git Branch", 'Peleman-Webshop-Package'),
            array($this, 'text_property_callback'),
            $this->page_slug,
            "pwp_git_update_branch",
            array(
                'option' => 'pwp_enable_f2d',
                'description' =>  __("Which git branch to pull from.", 'Peleman-Webshop-Package'),
            )
        );
    }
}
