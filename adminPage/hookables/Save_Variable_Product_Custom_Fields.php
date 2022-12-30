<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\Keys;
use PWP\includes\editor\PIE_Editor_Instructions;
use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\editor\Product_PIE_Data;
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
            ->set_unit_amount((int)$_POST[Product_Meta_Data::UNIT_AMOUNT][$loop] ?: 1)
            ->set_unit_price((float)$_POST[Product_Meta_Data::UNIT_PRICE][$loop])
            ->set_unit_code($_POST[Product_Meta_Data::UNIT_CODE][$loop])
            ->set_uses_pdf_content(
                isset($_POST[Product_Meta_Data::USE_PDF_CONTENT_KEY][$loop])
            )
            ->set_pdf_max_pages((int)$_POST[Product_Meta_Data::PDF_MAX_PAGES_KEY][$loop])
            ->set_pdf_min_pages((int)$_POST[Product_Meta_Data::PDF_MIN_PAGES_KEY][$loop])
            ->set_pdf_height((int)$_POST[Product_Meta_Data::PDF_HEIGHT_KEY][$loop])
            ->set_pdf_width((int)$_POST[Product_Meta_Data::PDF_WIDTH_KEY][$loop])
            ->set_price_per_page((float)$_POST[Product_Meta_Data::PDF_PRICE_PER_PAGE_KEY][$loop])
            ->set_editor(
                esc_attr(sanitize_text_field($_POST[Product_Meta_Data::EDITOR_ID_KEY][$loop]))
            )->set_custom_add_to_cart_label(
                esc_attr(sanitize_text_field($_POST[Product_Meta_Data::CUSTOM_LABEL_KEY][$loop]))
            )->set_override_thumbnail((bool)$_POST[Product_Meta_Data::OVERRIDE_CART_THUMB][$loop]);

        $pie_data->set_template_id(
            esc_attr(sanitize_text_field(
                $_POST[Product_PIE_Data::PIE_TEMPLATE_ID_KEY][$loop]
            ))
        );

        //PIE specific data
        $pie_data
            ->set_design_id(
                esc_attr(sanitize_text_field($_POST[Product_PIE_Data::DESIGN_ID_KEY][$loop]))
            )->set_color_code(
                esc_attr(sanitize_text_field($_POST[Product_PIE_Data::COLOR_CODE_KEY][$loop]))
            )->set_background_id(
                esc_attr(sanitize_text_field($_POST[Product_PIE_Data::BACKGROUND_ID_KEY][$loop]))
            )->set_uses_image_upload(
                isset($_POST[Product_PIE_Data::USE_IMAGE_UPLOAD_KEY][$loop])
            )->set_autofill(
                isset($_POST[Product_PIE_Data::AUTOFILL_KEY][$loop])
            )->set_num_pages(
                (int)esc_attr(sanitize_text_field($_POST[Product_PIE_Data::NUM_PAGES_KEY][$loop]))
            )->set_format_id(
                esc_attr(sanitize_text_field($_POST[Product_PIE_Data::FORMAT_ID_KEY][$loop]))
            )->set_max_images(
                (int)esc_attr(sanitize_text_field($_POST[Product_PIE_Data::MAX_IMAGES_KEY][$loop]))
            )->set_min_images(
                (int)esc_attr(sanitize_text_field($_POST[Product_PIE_Data::MIN_IMAGES_KEY][$loop]))
            )->set_editor_instructions(
                explode(' ', ($_POST[PIE_Editor_Instructions::EDITOR_INSTRUCTIONS_KEY][$loop]))
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
