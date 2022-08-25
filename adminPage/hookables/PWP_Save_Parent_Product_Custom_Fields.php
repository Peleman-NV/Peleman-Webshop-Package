<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

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
            isset($_POST[PWP_Product_Meta_Data::USE_PDF_CONTENT])
        )->set_custom_add_to_cart_label(
            esc_attr(sanitize_text_field($_POST[PWP_Product_Meta_Data::CUSTOM_LABEL]))
        )->set_editor(
            esc_attr(sanitize_text_field($_POST[PWP_Product_Meta_Data::EDITOR_ID]))
        );

        if ($product instanceof WC_Product_Simple) {
            $pieData = $editorMeta->pie_data();
            $imaxelData = $editorMeta->imaxel_data();

            $pieData->set_design_id(
                esc_attr(sanitize_text_field(
                    $_POST[PWP_Product_PIE_Data::DESIGN_ID_KEY]
                ))
            )->set_color_code(
                esc_attr(sanitize_text_field(
                    $_POST[PWP_Product_PIE_Data::COLOR_CODE_KEY]
                ))
            )->set_background_id(
                esc_attr(sanitize_text_field(
                    $_POST[PWP_Product_PIE_Data::BACKGROUND_ID_KEY]
                ))
            )->set_uses_image_upload(
                isset(
                    $_POST[PWP_Product_PIE_Data::USE_IMAGE_UPLOAD]
                )
            )->set_max_images(
                (int)esc_attr(sanitize_text_field(
                    $_POST[PWP_Product_PIE_Data::MAX_IMAGES]
                ))
            )->set_min_images(
                (int)esc_attr(sanitize_text_field(
                    $_POST[PWP_Product_PIE_Data::MIN_IMAGES]
                ))
            );

            $imaxelData->set_template_id(
                esc_attr(sanitize_text_field(
                    $_POST[PWP_Product_IMAXEL_Data::TEMPLATE_ID_KEY]
                ))
            )->set_variant_id(
                esc_attr(sanitize_text_field(
                    $_POST[PWP_Product_IMAXEL_Data::VARIANT_ID_KEY]
                ))
            );
        }

        $product->save_meta_data();
        $editorMeta->update_meta_data();
        $editorMeta->save();
    }
}
