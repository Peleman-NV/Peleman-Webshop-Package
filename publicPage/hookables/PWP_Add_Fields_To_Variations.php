<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;

class PWP_Add_Fields_To_Variations extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_available_variation', 'add_extra_value', 10, 3);
    }

    public function add_extra_value(array $data, \WC_Product_Variable $product, \WC_Product_Variation $variation)
    {
        if ($variation->get_meta(PWP_Product_Meta_Data::USE_PDF_CONTENT)) {
            $data['variation_description'] .= '<p><b>beep<b></p>';
        }

        return $data;
    }
}
