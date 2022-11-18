<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Enqueue_Public_Styles extends Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('wp_enqueue_scripts', 'enqueue_public_styles', 12);
    }

    public function enqueue_public_styles(): void
    {
        wp_enqueue_style(
            'pwp-products',
            plugins_url('../css/product-page-style.css', __FILE__),
            array(),
            (string)rand(0, 2000),
            'all'
        );
        wp_enqueue_style('dashicons');
    }
}
