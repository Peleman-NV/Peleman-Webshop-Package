<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class='pwp-upload-form'>
    <div class='pwpi-upload-parameters'>
        <div class='thumbnail-container'>
            <img id='pwp-thumbnail' />
        </div>
        <table>
            <tbody>
                <tr>
                    <td><?= esc_html__('Maximum file size', PWP_TEXT_DOMAIN); ?></td>
                    <td><?= $max_file_size; ?></td>
                </tr>
                <tr>
                    <td><?= esc_html__('PDF page Width (mm)', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-width'><?= $pdf_width ?: ''; ?></td>
                </tr>
                <tr>
                    <td><?= esc_html__('PDF Page Height (mm)', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-height'><?= $pdf_height ?: ''; ?></td>
                </tr>
                <tr>
                    <td><?= esc_html__('Minimum Page Count', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-min-pages'><?= $pdf_min_pages ?: ''; ?></td>
                </tr>
                <tr>
                    <td><?= esc_html__('Maximum Page Count', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-max-pages'><?= $pdf_max_pages ?: ''; ?></td>
                </tr>
                <tr>
                    <td><?= esc_html__('Price Per Page', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-price-per-page'><?= get_woocommerce_currency_symbol() . $price_per_page ?: '' ?></td>
                </tr>
            <tbody>
        </table>
        <!-- <div class='ppi-upload-form ppi-hidden'>
            <label class='upload-label upload-disabled' for='file-upload'><?= $button_label; ?></label>
            <input id='file-upload' type='file' accept='application/pdf' name='pdf_upload' style='display: none;'>
        </div> -->
        <div id='upload-info' class='pwp-upload-form pwp-hidden'>
            <label class='upload-label button' for='file-upload'><?= esc_html__('upload .pdf file', PWP_TEXT_DOMAIN); ?></label>
            <input id='file-upload' type='file' accept='application/pdf' name='pdf_upload' size='<?= $size; ?>'>
        </div>
    </div>
</div>