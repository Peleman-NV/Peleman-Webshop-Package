<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\PWP_Abstract_Action_Component;

class PWP_Save_Variable_Custom_Fields extends PWP_Abstract_Action_Component
{
    public function __construct()
    {
        // $this->loader->add_action('woocommerce_save_product_variation', $plugin_admin, 'ppi_persist_custom_field_variations', 11, 2);

        parent::__construct(
            'woocommerce_save_product_variation',
            'save_variables',
            11,
            2
        );
    }

    public function save_variables(int $variation_id, int $loop)
    {
        $data = array();
        foreach ($_POST as $key => $property) {
            $data[$key] = sanitize_text_field($property);
        }

        var_dump($data);
    }
}
