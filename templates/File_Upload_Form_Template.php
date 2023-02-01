<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class='pwp-upload-form <?php echo esc_attr($enabled ? '' :  'pwp-hidden'); ?>'>
    <div class='pwp-upload-parameters' style="display: inline-block">
        <p>
            <?php echo esc_html__('Your full price will be calculated in the cart according to the number of pages of your content PDF.', PWP_TEXT_DOMAIN); ?>
            <?php echo esc_html__('The price per page for this product equals', PWP_TEXT_DOMAIN); ?> <span class="price-per-page"><?php echo wc_price($price_per_page) ?: '' ?></span>
        </p>
        <table class="pwp-pdf-table">
            <tbody>
                <tr>
                    <td><?php echo esc_html__('Maximum file size', PWP_TEXT_DOMAIN); ?></td>
                    <td><?php echo $max_file_size; ?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('PDF page width (mm)', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-width'><?php echo esc_attr($pdf_width ?: ''); ?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('PDF page height (mm)', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-height'><?php echo esc_attr($pdf_height ?: ''); ?></td>
                </tr> G
                <tr>
                    <td><?php echo esc_html__('Minimum page count', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-min-pages'><?php echo esc_attr($pdf_min_pages ?: ''); ?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Maximum page count', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-max-pages'><?php echo esc_attr($pdf_max_pages ?: ''); ?></td>
                </tr>
                <tr>
                    <td><?php echo esc_html__('Price per page', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value price-per-page' id='content-price-per-page'><?php echo wc_price($price_per_page) ?: '' ?></td>
                </tr>
            </tbody>
        </table>
        <div id='pwp-upload-info'>
            <label class='pwp-upload-label' for='pwp-file-upload'>
                <i class="icon-doc"></i><?php echo esc_html__('Drag or upload your PDF file here', PWP_TEXT_DOMAIN); ?>
                <input class='pwp-upload-field' id='pwp-file-upload' type='file' accept='application/pdf' name='pdf-upload' size='<?php echo esc_html($size); ?>' required />
                <br /><span id="pwp-upload-filename" style="color: green; margin-top: 20px; font-weight: 500; font-size: 16px;"></span>
            </label>
            <div class='pwp-thumbnail-container'>
                <canvas id='pwp-pdf-canvas' width="250" style="display:none"></canvas>
            </div>
            <button id="pwp-file-clear" type="button" style="display:none;"><?php echo esc_html__('Remove PDF', PWP_TEXT_DOMAIN); ?></button>
        </div>
        <div>
            <table id="pwp-pdf-pages-pricing" style="display: none">
                <tbody>
                    <tr>
                        <td><?php echo esc_html__('PDF pages: ', PWP_TEXT_DOMAIN); ?></td>
                        <td id="pwp-pdf-pages"></td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Added cost (excl. VAT): ', PWP_TEXT_DOMAIN); ?></td>
                        <td>
                            <strong id="pwp-pdf-price" class="param-value"><?php echo wc_price(0); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo esc_html__('Estimated total cost: ', PWP_TEXT_DOMAIN); ?></td>
                        <td>
                            <strong id="pwp-pdf-total" class="param-value"><?php echo wc_price(0); ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>