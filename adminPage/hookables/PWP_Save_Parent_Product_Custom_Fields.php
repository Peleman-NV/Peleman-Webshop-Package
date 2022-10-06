<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PWP_Keys;
use PWP\includes\editor\PWP_PIE_Editor_Instructions;
use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\editor\PWP_Product_IMAXEL_Data;
use PWP\includes\editor\PWP_Product_PIE_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_hookable;
use WC_Product_Simple;
use WP_Post;

class PWP_Save_Parent_Product_Custom_Fields extends PWP_Abstract_Action_hookable
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
        $editorMeta = new PWP_Product_Meta_Data($product);

        if (!isset($product)) {
            error_log("tried to save parameters for product with id {$postId}, but something went wrong");
            return;
        }

        $editorMeta->set_uses_pdf_content(
            isset($_POST[PWP_Keys::USE_PDF_CONTENT_KEY])
        )
            ->set_pdf_max_pages((int)$_POST[PWP_Keys::PDF_MAX_PAGES_KEY])
            ->set_pdf_min_pages((int)$_POST[PWP_Keys::PDF_MIN_PAGES_KEY])
            ->set_pdf_height((int)$_POST[PWP_Keys::PDF_HEIGHT_KEY])
            ->set_pdf_width((int)$_POST[PWP_Keys::PDF_WIDTH_KEY])
            ->set_price_per_page((float)$_POST[PWP_Keys::PDF_PRICE_PER_PAGE_KEY])
            ->set_custom_add_to_cart_label(
                esc_attr(sanitize_text_field($_POST[PWP_Keys::CUSTOM_LABEL_KEY]))
            )
            ->set_editor(
                esc_attr(sanitize_text_field($_POST[PWP_Keys::EDITOR_ID_KEY]))
            );

        if ($product instanceof WC_Product_Simple) {
            $pieData = $editorMeta->pie_data();
            $imaxelData = $editorMeta->imaxel_data();

            $pieData
                ->set_design_id(
                    esc_attr(sanitize_text_field($_POST[PWP_Keys::DESIGN_ID_KEY]))
                )
                ->set_color_code(
                    esc_attr(sanitize_text_field($_POST[PWP_Keys::COLOR_CODE_KEY]))
                )
                ->set_background_id(
                    esc_attr(sanitize_text_field($_POST[PWP_Keys::BACKGROUND_ID_KEY]))
                )
                ->set_uses_image_upload(
                    isset($_POST[PWP_Keys::USE_IMAGE_UPLOAD_KEY])
                )
                ->set_autofill(
                    isset($_POST[PWP_Keys::AUTOFILL_KEY])
                )
                ->set_num_pages(
                    (int)esc_attr(sanitize_text_field($_POST[PWP_Keys::NUM_PAGES_KEY]))
                )
                ->set_format_id(
                    esc_attr(sanitize_text_field($_POST[PWP_Keys::FORMAT_ID_KEY]))
                )
                ->set_max_images(
                    (int)esc_attr(sanitize_text_field($_POST[PWP_Keys::MAX_IMAGES_KEY]))
                )
                ->set_min_images(
                    (int)esc_attr(sanitize_text_field($_POST[PWP_Keys::MIN_IMAGES_KEY]))
                )
                ->set_editor_instructions(
                    explode(
                        ' ',
                        esc_attr($_POST[PWP_Keys::EDITOR_INSTRUCTIONS_KEY])
                    )
                );

            $imaxelData->set_template_id(
                esc_attr(sanitize_text_field(
                    $_POST[PWP_Keys::IMAXEL_TEMPLATE_ID_KEY]
                ))
            )->set_variant_id(
                esc_attr(sanitize_text_field(
                    $_POST[PWP_Keys::IMAXEL_VARIANT_ID_KEY]
                ))
            );
        }

        $product->save_meta_data();
        $editorMeta->update_meta_data();
        $editorMeta->save_meta_data();
    }
}
