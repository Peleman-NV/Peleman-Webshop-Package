<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_Keys;
use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\includes\validation\PWP_Validate_File_Size;
use PWP\templates\PWP_Template;
use WC_Product_Simple;

class PWP_Display_PDF_Upload_Form extends PWP_Abstract_Action_Hookable

{
    private PWP_Template $template;
    public function __construct(PWP_Template $template)
    {
        parent::__construct('woocommerce_before_add_to_cart_button', 'render_add_pdf_upload_form', 10, 1);
        $this->template = $template;
    }

    public function render_add_pdf_upload_form(): void
    {
        //this will work for simple product.
        $product = wc_get_product();
        $usesPdf = $product->get_meta(PWP_Keys::USE_PDF_CONTENT_KEY);
        if (!$usesPdf) {
            return;
        }
        $isVariable = $product instanceof \WC_Product_Simple;
        $meta = new PWP_Product_Meta_Data($product);
        $params = array(
            'enabled' => $isVariable || $usesPdf,
            'button_label' => esc_html__('Click here to upload your PDF file', PWP_TEXT_DOMAIN),
            'max_file_size' => '200 MB',
            'size' => (int)ini_get('upload_max_filesize') * PWP_Validate_File_Size::MB,
            'pdf_width' => $meta->get_pdf_width() ? "{$meta->get_pdf_width()} mm" : '',
            'pdf_height' => $meta->get_pdf_height() ? "{$meta->get_pdf_height()}mm" : '',
            'pdf_min_pages' => $meta->get_pdf_min_pages() ? $meta->get_pdf_min_pages() : '',
            'pdf_max_pages' => $meta->get_pdf_max_pages() ?: '',
            'price_per_page' => $meta->get_price_per_page() ?: '',
        );

        echo $this->template->render('PWP_File_Upload_Form_Template', $params);
    }
}
