<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;

class PWP_Admin_Enqueue_Scripts extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('admin_enqueue_scripts', 'enqueue_scripts');
    }

    public function enqueue_scripts()
    {
        $randomVersionNumber = rand(0, 1000);
        wp_enqueue_script('pwp_admin_product_page_script', plugins_url('Peleman-Webshop-Package/adminPage/js/admin-ui.js'), array(), $randomVersionNumber);
    }
}
