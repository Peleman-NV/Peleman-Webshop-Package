<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class='ppi-upload-form'>
    <div class='ppi-upload-parameters'>
        <div class='thumbnail-container'>
            <img id='ppi-thumbnail' />
        </div>
        <table>
            <tbody>
                <tr>
                    <td><?= esc_html__('Maximum file size', PWP_TEXT_DOMAIN); ?></td>
                    <td><?= $max_file_size; ?></td>
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
            <label class='upload-label upload-disabled' for='file-upload'><?= $button_label; ?></label>
            <input id='file-upload' type='file' accept='application/pdf' name='pdf_upload' style='display: none;'>
        </div>
        <div id='upload-info'></div>
    </div>
    <label class='upload-label' for='file-upload'><?= esc_html__('Click here to upload your PDF file', PWP_TEXT_DOMAIN); ?></label>
    <input id='file-upload' type='file' accept='application/pdf' name='pdf_upload' style='display: none;'>
</div>
<div id='upload-info'></div>