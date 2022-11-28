<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\Keys;
use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

/**
 * Save child product custom variables added by the plugin
 */
class Save_Variable_Product_Custom_Fields extends Abstract_Action_Hookable
{
    public function __construct()
    {
        // $this->loader->add_action('woocommerce_save_product_variation', $plugin_admin, 'ppi_persist_custom_field_variations', 11, 2);

        parent::__construct(
            'woocommerce_save_product_variation',
            'save_variables',
            11,
            2
        );
    }

    public function save_variables(int $variation_id, int $loop)
    {
        $editor_data = new Product_Meta_Data(wc_get_product($variation_id));
        $pie_data = $editor_data->pie_data();
        $imaxel_data = $editor_data->imaxel_data();

        $editor_data
            ->set_cart_units((int)$_POST[Keys::UNIT_AMOUNT][$loop] ?: 1)
            ->set_cart_price((float)$_POST[keys::UNIT_PRICE][$loop])
            ->set_uses_pdf_content(
                isset($_POST[Keys::USE_PDF_CONTENT_KEY][$loop])
            )
            ->set_pdf_max_pages((int)$_POST[Keys::PDF_MAX_PAGES_KEY][$loop])
            ->set_pdf_min_pages((int)$_POST[Keys::PDF_MIN_PAGES_KEY][$loop])
            ->set_pdf_height((int)$_POST[Keys::PDF_HEIGHT_KEY][$loop])
            ->set_pdf_width((int)$_POST[Keys::PDF_WIDTH_KEY][$loop])
            ->set_price_per_page((float)$_POST[Keys::PDF_PRICE_PER_PAGE_KEY][$loop])
            ->set_editor(
                esc_attr(sanitize_text_field($_POST[Keys::EDITOR_ID_KEY][$loop]))
            )->set_custom_add_to_cart_label(
                esc_attr(sanitize_text_field($_POST[Keys::CUSTOM_LABEL_KEY][$loop]))
            )->set_override_thumbnail((bool)$_POST[Keys::OVERRIDE_CART_THUMBNAIL][$loop]);

        $pie_data->set_template_id(
            esc_attr(sanitize_text_field(
                $_POST[Keys::PIE_TEMPLATE_ID_KEY][$loop]
            ))
        );

        //PIE specific data
        $pie_data
            ->set_design_id(
                esc_attr(sanitize_text_field($_POST[Keys::DESIGN_ID_KEY][$loop]))
            )->set_color_code(
                esc_attr(sanitize_text_field($_POST[Keys::COLOR_CODE_KEY][$loop]))
            )->set_background_id(
                esc_attr(sanitize_text_field($_POST[Keys::BACKGROUND_ID_KEY][$loop]))
            )->set_uses_image_upload(
                isset($_POST[Keys::USE_IMAGE_UPLOAD_KEY][$loop])
            )->set_autofill(
                isset($_POST[Keys::AUTOFILL_KEY][$loop])
            )->set_num_pages(
                (int)esc_attr(sanitize_text_field($_POST[Keys::NUM_PAGES_KEY][$loop]))
            )->set_format_id(
                esc_attr(sanitize_text_field($_POST[Keys::FORMAT_ID_KEY][$loop]))
            )->set_max_images(
                (int)esc_attr(sanitize_text_field($_POST[Keys::MAX_IMAGES_KEY][$loop]))
            )->set_min_images(
                (int)esc_attr(sanitize_text_field($_POST[Keys::MIN_IMAGES_KEY][$loop]))
            )->set_editor_instructions(
                explode(' ', ($_POST[Keys::EDITOR_INSTRUCTIONS_KEY][$loop]))
            );

        //IMAXEL specific data
        // $imaxel_data->set_template_id(
        //     esc_attr(sanitize_text_field(
        //         $_POST[Keys::IMAXEL_TEMPLATE_ID_KEY][$loop]
        //     ))
        // )->set_variant_id(
        //     esc_attr(sanitize_text_field(
        //         $_POST[Keys::IMAXEL_VARIANT_ID_KEY][$loop]
        //     ))
        // );

        $editor_data->update_meta_data();
        $editor_data->save_meta_data();
    }
}
