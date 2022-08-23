<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;

class PWP_Enqueue_Public_Styles extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('wp_enqueue_scripts', 'enqueue_public_styles', 12);
    }

    public function enqueue_public_styles(): void
    {
        wp_enqueue_style(
            'pwp-products',
            plugins_url('Peleman-Webshop-Package/publicPage/css/product-page-style.css'),
            array(),
            (string)rand(0, 2000),
            'all'
        );
        wp_enqueue_style('dashicons');
    }
}
