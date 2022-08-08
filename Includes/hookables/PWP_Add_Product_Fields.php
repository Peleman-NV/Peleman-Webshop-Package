<?php

declare(strict_types=1);

namespace PWP\includes\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_hookable;

class PWP_Add_Product_Fields extends PWP_Abstract_Action_hookable
{
    public function __construct()
    {
        parent::__construct(
            'woocommerce_before_add_to_cart_button',
            'display_field',
            10,
            1
        );
    }

    public function display_field(): void
    {
        //add hidden fields to the add_to_cart form and button
?>
        <input type="hidden" name="project_id" value="12">
        <input type="hidden" name="editor" value="PIE">
<?php
    }
}
