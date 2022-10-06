<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_Keys;
use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;

class PWP_Display_PDF_Fields_On_Variations extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_available_variation', 'add_extra_value', 10, 3);
    }

    public function add_extra_value(array $data, \WC_Product_Variable $product, \WC_Product_Variation $variation)
    {
        if ($variation->get_meta(PWP_Keys::USE_PDF_CONTENT_KEY)) {
            do_action('pwp_render_pdf_upload_form');
        }

        return $data;
    }
}
