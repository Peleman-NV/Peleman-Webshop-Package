<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

use function WP_CLI\Utils\get_plugin_name;

class Admin_Enqueue_Styles extends Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('admin_enqueue_scripts', 'enqueue_styles');
    }

    public function enqueue_styles(): void
    {
        $randomVersionNumber = rand(0, 1000);
        wp_enqueue_style(
            'pwp_admin_stylesheet',
            plugins_url('../css/style.css', __FILE__),
            array(),
            $randomVersionNumber,
            'all'
        );
    }
}
