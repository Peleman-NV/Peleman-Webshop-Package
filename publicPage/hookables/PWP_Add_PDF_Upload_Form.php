<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;

class PWP_Add_PDF_Upload_Form extends PWP_Abstract_Action_Hookable

{
    public function __construct()
    {
        parent::__construct('woocommerce_before_add_to_cart_button', 'render_add_pdf_upload_form', 10, 1);
    }

    public function render_add_pdf_upload_form(): void
    {
        $buttonLabel = esc_html__('Click here to upload your PDF file', PWP_TEXT_DOMAIN);
        $maxFileSize = "100 MB";

?>
        <div class='ppi-upload-parameters'>
            <div class='thumbnail-container'>
                <img id='ppi-thumbnail' />
            </div>
            <table>
                <tbody>
                    <tr>
                        <td><?= esc_html__('Maximum file size', PWP_TEXT_DOMAIN); ?></td>
                        <td><?= $maxFileSize; ?></td>
                    </tr>
                    <tr>
                        <td><?= esc_html__('PDF page Width', PWP_TEXT_DOMAIN); ?></td>
                        <td class='param-value' id='content-width'></td>
                    </tr>
                    <tr>
                        <td><?= esc_html__('PDF Page Height', PWP_TEXT_DOMAIN); ?></td>
                        <td class='param-value' id='content-height'></td>
                    </tr>
                    <tr>
                        <td><?= esc_html__('Minimum Page Count', PWP_TEXT_DOMAIN); ?></td>
                        <td class='param-value' id='content-min-pages'></td>
                    </tr>
                    <tr>
                        <td><?= esc_html__('Maximum Page Count', PWP_TEXT_DOMAIN); ?></td>
                        <td class='param-value' id='content-max-pages'></td>
                    </tr>
                    <tr>
                        <td><?= esc_html__('Price Per Page', PWP_TEXT_DOMAIN); ?></td>
                        <td class='param-value' id='content-price-per-page'></td>
                    </tr>
                <tbody>
            </table>
            <div class='ppi-upload-form ppi-hidden'>
                <label class='upload-label upload-disabled' for='file-upload'><?= $buttonLabel; ?></label>
                <input id='file-upload' type='file' accept='application/pdf' name='pdf_upload' style='display: none;'>
            </div>
            <div id='upload-info'></div>
    <?php
    }
}
