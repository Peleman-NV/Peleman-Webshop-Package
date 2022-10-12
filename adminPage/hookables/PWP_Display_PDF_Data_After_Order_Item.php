<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;

class PWP_Display_PDF_Data_After_Order_Item extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_after_order_itemmeta', 'display_pdf_line', 10, 2);
        $this->add_hook('woocommerce_order_item_meta_end');
    }

    public function display_pdf_line(int $item_id, \WC_Order_Item $item): void
    {
        if (!($item->get_meta('_pdf_data'))) return;
        $data = $item->get_meta('_pdf_data');
        $id = $data['id'];
        $name = $data['pdf_name'];

        $download = home_url('wp-json/pwp/v1/pdf/' . $id);
?>
        <div><a download=<?= $name; ?> href=<?= $download ?>>download <?= $name ?></a></div>
<?php
    }
}
