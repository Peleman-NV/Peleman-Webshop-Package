<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class='pwp-upload-form <?= $enabled ? '' :  'pwp-hidden'; ?>'>
    <div class='pwp-upload-parameters'>
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
                    <td><?= esc_html__('PDF page width (mm)', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-width'><?= $pdf_width ?: ''; ?></td>
                </tr>
                <tr>
                    <td><?= esc_html__('PDF page height (mm)', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-height'><?= $pdf_height ?: ''; ?></td>
                </tr>
                <tr>
                    <td><?= esc_html__('Minimum page count', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-min-pages'><?= $pdf_min_pages ?: ''; ?></td>
                </tr>
                <tr>
                    <td><?= esc_html__('Maximum page count', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-max-pages'><?= $pdf_max_pages ?: ''; ?></td>
                </tr>
                <tr>
                    <td><?= esc_html__('Price Per Page', PWP_TEXT_DOMAIN); ?></td>
                    <td class='param-value' id='content-price-per-page'><?= wc_price($price_per_page) ?: '' ?></td>
                </tr>
            <tbody>
        </table>
        <div id='pwp-upload-info'>
            <label class='pwp-upload-label' for='pwp-file-upload'>
                <i class="icon-doc"></i><?= esc_html__('Drag or upload your PDF file here', PWP_TEXT_DOMAIN); ?>
                <input class='pwp-upload-field' id='pwp-file-upload' type='file' accept='application/pdf' name='pdf-upload' size='<?= $size; ?>' required />
                <br/><span id="pwp-upload-filename" style="color: green; margin-top: 20px; font-weight: 500; font-size: 16px;"></span>
            </label>
            
            <script>
                let input = document.getElementById("pwp-file-upload");
                let imageName = document.getElementById("pwp-upload-filename")


               input.addEventListener("change", ()=>{
                    let inputImage = document.querySelector("input[type=file]").files[0];


                   imageName.innerText = inputImage.name;
                })
            </script>
        </div>
    </div>
</div>