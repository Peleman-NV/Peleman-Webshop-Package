<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

/**
 * Saves/Updates the settings from the PWP main control panel.
 */
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

        register_setting($group, 'pwp_project_cleanup_cutoff_days', array(
            'type' => 'string',
            'description' => 'amount of days before an uploaded pdf project is removed. Only pdfs which have not been ordered will be deleted.',
            'sanitize_callback' => 'sanitize_key',
            'show_in_rest' => false,
            'default' => 15,
        ));
    }
}
