<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;

class PWP_Register_Editor_Options extends PWP_Abstract_Action_Hookable

{
    public function __construct()
    {
        parent::__construct('admin_init', 'register_options');
    }

    public function register_options()
    {
        register_setting('editorOptions-group', 'pie_domain', array(
            'type' => 'string',
            'description' => 'base Site Address of the PIE editor',
            'sanitize_callback' => 'esc_url_raw',
            'show_in_rest' => false,
            'default' => ''
        ));

        register_setting('editorOptions-group', 'pie_customer_id', array(
            'type' => 'string',
            'description' => 'customer id for the PIE Editor',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => false,
            'default' => ''
        ));

        register_setting('editorOptions-group', 'pie_api_key', array(
            'type' => 'string',
            'description' => 'customer api key for PIE Editor',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => false,
            'default' => ''
        ));

        register_setting('editorOptions-group', 'pwp_imaxel_private_key', array(
            'type' => 'string',
            'description' => '',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => false,
            'default' => ''
        ));

        register_setting('editorOptions-group', 'pwp_imaxel_public_key', array(
            'type' => 'string',
            'description' => '',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => false,
            'default' => ''
        ));

        register_setting('editorOptions-group', 'pwp_imaxel_shop_code', array(
            'type' => 'string',
            'description' => '',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'show_in_rest' => false,
            'default' => ''
        ));
    }
}
