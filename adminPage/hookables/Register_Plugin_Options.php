<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Register_Plugin_Options extends Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('admin_init', 'register_webshop_options');
    }

    public function register_webshop_options()
    {
        $group = 'webshopOptions-group';

        register_setting($group, 'pwp_customize_label', array(
            'type' => 'string',
            'description' => 'label for shop archive; to be displayed on products that require user customization/uploads',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => true,
            'default' => 'Customize product',
        ));

        register_setting($group, 'pwp_archive_var_label', array(
            'type' => 'string',
            'description' => 'label for shop archive; to be displayed on variable products that require user customization/uploads',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => true,
            'default' => 'Choose Options',
        ));
    }
}