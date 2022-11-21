<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\Keys;
use PWP\includes\editor\PIE_Editor_Instructions;
use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\editor\Product_IMAXEL_Data;
use PWP\includes\editor\Product_PIE_Data;
use PWP\includes\hookables\abstracts\Abstract_Action_hookable;
use WC_Product_Simple;
use WP_Post;

class Save_Parent_Product_Custom_Fields extends Abstract_Action_hookable
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

        $editorMeta->set_cart_units((int)$_POST[Keys::UNIT_AMOUNT] ?: 1)
            ->set_cart_price((float)$_POST[keys::UNIT_PRICE])
            ->set_uses_pdf_content(
                isset($_POST[Keys::USE_PDF_CONTENT_KEY])
            )
            ->set_pdf_max_pages((int)$_POST[Keys::PDF_MAX_PAGES_KEY])
            ->set_pdf_min_pages((int)$_POST[Keys::PDF_MIN_PAGES_KEY])
            ->set_pdf_height((int)$_POST[Keys::PDF_HEIGHT_KEY])
            ->set_pdf_width((int)$_POST[Keys::PDF_WIDTH_KEY])
            ->set_price_per_page((float)$_POST[Keys::PDF_PRICE_PER_PAGE_KEY])
            ->set_custom_add_to_cart_label(
                esc_attr(sanitize_text_field($_POST[Keys::CUSTOM_LABEL_KEY]))
            )
            ->set_editor(
                esc_attr(sanitize_text_field($_POST[Keys::EDITOR_ID_KEY]))
            )->set_override_thumbnail(isset($_POST[Keys::OVERRIDE_CART_THUMBNAIL]));

        if ($product instanceof WC_Product_Simple) {
            $pieData = $editorMeta->pie_data();
            $imaxelData = $editorMeta->imaxel_data();

            $pieData
                ->set_template_id(esc_attr(sanitize_text_field($_POST[Keys::PIE_TEMPLATE_ID_KEY])))
                ->set_design_id(esc_attr(sanitize_text_field($_POST[Keys::DESIGN_ID_KEY])))
                ->set_color_code(esc_attr(sanitize_text_field($_POST[Keys::COLOR_CODE_KEY])))
                ->set_background_id(esc_attr(sanitize_text_field($_POST[Keys::BACKGROUND_ID_KEY])))
                ->set_uses_image_upload(isset($_POST[Keys::USE_IMAGE_UPLOAD_KEY]))
                ->set_autofill(isset($_POST[Keys::AUTOFILL_KEY]))
                ->set_num_pages((int)esc_attr(sanitize_text_field($_POST[Keys::NUM_PAGES_KEY])))
                ->set_format_id(esc_attr(sanitize_text_field($_POST[Keys::FORMAT_ID_KEY])))
                ->set_max_images((int)esc_attr(sanitize_text_field($_POST[Keys::MAX_IMAGES_KEY])))
                ->set_min_images((int)esc_attr(sanitize_text_field($_POST[Keys::MIN_IMAGES_KEY])))
                ->set_editor_instructions(explode(' ', esc_attr(sanitize_text_field($_POST[Keys::EDITOR_INSTRUCTIONS_KEY]))));

            // $imaxelData
            //     ->set_template_id(esc_attr(sanitize_text_field($_POST[Keys::IMAXEL_TEMPLATE_ID_KEY])))
            //     ->set_variant_id(esc_attr(sanitize_text_field($_POST[Keys::IMAXEL_VARIANT_ID_KEY])));
        }

        $product->save_meta_data();
        $editorMeta->update_meta_data();
        $editorMeta->save_meta_data();
    }
}
