<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;

class PWP_Admin_Control_Panel extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('admin_menu', 'pwp_add_control_panel', 9);
    }

    public function pwp_add_control_panel(...$args): void
    {
        add_menu_page(
            __("Peleman Webshop Control Panel", PWP_TEXT_DOMAIN),
            "Peleman PWP",
            "manage_options",
            "Peleman_Control_Panel",
            array($this, 'render_panel'),
            'dashicons-hammer',
            120
        );
    }

    public function render_panel(): void
    {

    }
}
