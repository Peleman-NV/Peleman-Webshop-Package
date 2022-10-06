<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;

class PWP_Display_PDF_Data_In_Cart extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_after_cart_item_name', 'render_pdf_title', 9, 2);
    }

    public function render_pdf_title(array $cart_item, string $cart_item_key)
    {
        if (isset($cart_item['_pdf_data'])) {
            $id = $cart_item['_pdf_data']['id'];
            $filename = $cart_item['_pdf_data']['pdf_name'];
            $pages = $cart_item['_pdf_data']['pages'];

?>
            <div><strong>pdf</strong>: <?= $filename; ?></div>
            <div><strong>pages</strong>: <?= $pages; ?></div>
<?php
        }
    }
}
