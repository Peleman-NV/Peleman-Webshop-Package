<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Keys;
use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;
use PWP\includes\validation\Validate_File_Size;
use PWP\templates\Template;

/**
 * Displays and initialized a PDF form on the product page.
 */
class Display_PDF_Upload_Form extends Abstract_Action_Hookable

{
    private Template $template;
    public function __construct(Template $template)
    {
        parent::__construct('woocommerce_before_add_to_cart_button', 'render_add_pdf_upload_form', 10, 1);
        $this->template = $template;
    }

    public function render_add_pdf_upload_form(): void
    {
        //this will work for simple product.
        $product = wc_get_product();

        switch ($product->get_type()) {
            default:
            case 'simple':
                $this->display_pdf_data_form($product, (bool)$product->get_meta(Product_Meta_Data::USE_PDF_CONTENT_KEY));
                return;
            case 'variable':
            case 'variant':
                $this->display_pdf_data_form($product);
                return;
        }
    }

    private function display_pdf_data_form(\WC_Product $product, bool $enabled = false): void
    {
        $meta = new Product_Meta_Data($product);
        $params = array(
            'enabled' => $enabled,
            'button_label' => esc_html__('Click here to upload your PDF file', Peleman-Webshop-Package),
            'max_file_size' => '200 MB',
            'size' => (int)ini_get('upload_max_filesize') * Validate_File_Size::MB,
            'pdf_width' => $meta->get_pdf_width() ? "{$meta->get_pdf_width()} mm" : '',
            'pdf_height' => $meta->get_pdf_height() ? "{$meta->get_pdf_height()}mm" : '',
            'pdf_min_pages' => $meta->get_pdf_min_pages() ? $meta->get_pdf_min_pages() : '',
            'pdf_max_pages' => $meta->get_pdf_max_pages() ?: '',
            'price_per_page' => $meta->get_price_per_page() ?: '',
            'pdf_label' => esc_html__('upload your pdf here', Peleman-Webshop-Package),
        );

        echo $this->template->render('File_Upload_Form_Template', $params);
    }
}
