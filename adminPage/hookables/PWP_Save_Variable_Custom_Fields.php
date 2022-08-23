<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\editor\PWP_IMAXEL_Data;
use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_hookable;

class PWP_Save_Variable_Custom_Fields extends PWP_Abstract_Action_hookable
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
        $editor_data = new PWP_Product_Meta_Data(wc_get_product($variation_id));
        $pie_data = $editor_data->pie_data();
        $imaxel_data = $editor_data->imaxel_data();


        $editor_data->set_uses_pdf_content(
            isset($_POST[PWP_Product_Meta_Data::USE_PDF_CONTENT][$loop])
        );

        $editor_data->set_editor(
            esc_attr(sanitize_text_field($_POST[PWP_Product_Meta_Data::EDITOR_ID][$loop]))
        );

        $editor_data->set_custom_add_to_cart_label(
            esc_attr(sanitize_text_field($_POST[PWP_Product_Meta_Data::CUSTOM_LABEL][$loop]))
        );

        $pie_data->set_template_id(
            esc_attr(sanitize_text_field(
                $_POST[PWP_PIE_DATA::TEMPLATE_ID_KEY][$loop]
            ))
        );

        //PIE specific data
        $pie_data->set_design_id(
            esc_attr(sanitize_text_field(
                $_POST[PWP_PIE_DATA::DESIGN_ID_KEY][$loop]
            ))
        );

        $pie_data->set_color_code(
            esc_attr(sanitize_text_field(
                $_POST[PWP_PIE_DATA::COLOR_CODE_KEY][$loop]
            ))
        );

        $pie_data->set_background_id(
            esc_attr(sanitize_text_field(
                $_POST[PWP_PIE_DATA::BACKGROUND_ID_KEY][$loop]
            ))
        );

        //IMAXEL specific data
        $imaxel_data->set_template_id(
            esc_attr(sanitize_text_field(
                $_POST[PWP_IMAXEL_Data::TEMPLATE_ID_KEY][$loop]
            ))
        );

        $imaxel_data->set_variant_id(
            esc_attr(sanitize_text_field(
                $_POST[PWP_IMAXEL_Data::VARIANT_ID_KEY][$loop]
            ))
        );

        $editor_data->update_meta_data();
        $editor_data->save();
    }
}
