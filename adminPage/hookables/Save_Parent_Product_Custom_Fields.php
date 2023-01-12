<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\Keys;
use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\editor\Product_PIE_Data;
use PWP\includes\editor\PIE_Editor_Instructions;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;
use WC_Product_Simple;
use WP_Post;

/**
 * Save parent product custom data added by the plugin.
 */
class Save_Parent_Product_Custom_Fields extends Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'woocommerce_process_product_meta',
            'save_variables',
            11,
            2
        );
    }

    public function save_variables(int $postId, WP_Post $post): void
    {
        $product = wc_get_product($postId);
        $editorMeta = new Product_Meta_Data($product);

        if (!isset($product)) {
            error_log("tried to save parameters for product with id {$postId}, but something went wrong");
            return;
        }

        $editorMeta->set_unit_amount((int)$_POST[Product_Meta_Data::UNIT_AMOUNT] ?: 1)
            ->set_unit_price((float)$_POST[Product_Meta_Data::UNIT_PRICE])
            ->set_unit_code($_POST[Product_Meta_Data::UNIT_CODE])
            ->set_uses_pdf_content(
                isset($_POST[Product_Meta_Data::USE_PDF_CONTENT_KEY])
            )
            ->set_pdf_max_pages((int)$_POST[Product_Meta_Data::PDF_MAX_PAGES_KEY])
            ->set_pdf_min_pages((int)$_POST[Product_Meta_Data::PDF_MIN_PAGES_KEY])
            ->set_pdf_height((int)$_POST[Product_Meta_Data::PDF_HEIGHT_KEY])
            ->set_pdf_width((int)$_POST[Product_Meta_Data::PDF_WIDTH_KEY])
            ->set_price_per_page((float)$_POST[Product_Meta_Data::PDF_PRICE_PER_PAGE_KEY])
            ->set_custom_add_to_cart_label(
                esc_attr(sanitize_text_field($_POST[Product_Meta_Data::CUSTOM_LABEL_KEY]))
            )
            ->set_editor(
                esc_attr(sanitize_text_field($_POST[Product_Meta_Data::EDITOR_ID_KEY]))
            )->set_override_thumbnail(isset($_POST[Product_Meta_Data::OVERRIDE_CART_THUMB]));

        if ($product instanceof WC_Product_Simple) {
            $pieData = $editorMeta->pie_data();

            $pieData
                ->set_template_id(esc_attr(sanitize_text_field($_POST[Product_PIE_Data::PIE_TEMPLATE_ID_KEY])))
                ->set_design_id(esc_attr(sanitize_text_field($_POST[Product_PIE_Data::DESIGN_ID_KEY])))
                ->set_color_code(esc_attr(sanitize_text_field($_POST[Product_PIE_Data::COLOR_CODE_KEY])))
                ->set_background_id(esc_attr(sanitize_text_field($_POST[Product_PIE_Data::BACKGROUND_ID_KEY])))
                ->set_uses_image_upload(isset($_POST[Product_PIE_Data::USE_IMAGE_UPLOAD_KEY]))
                ->set_autofill(isset($_POST[Product_PIE_Data::AUTOFILL_KEY]))
                ->set_num_pages((int)esc_attr(sanitize_text_field($_POST[Product_PIE_Data::NUM_PAGES_KEY])))
                ->set_format_id(esc_attr(sanitize_text_field($_POST[Product_PIE_Data::FORMAT_ID_KEY])))
                ->set_max_images((int)esc_attr(sanitize_text_field($_POST[Product_PIE_Data::MAX_IMAGES_KEY])))
                ->set_min_images((int)esc_attr(sanitize_text_field($_POST[Product_PIE_Data::MIN_IMAGES_KEY])))
                ->parse_instruction_array($_POST);
        }

        $product->save_meta_data();
        $editorMeta->update_meta_data();
        $editorMeta->save_meta_data();
    }
}
