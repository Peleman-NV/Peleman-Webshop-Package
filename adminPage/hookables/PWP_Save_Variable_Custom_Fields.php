<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

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
        $pie_data = new PWP_PIE_Data($variation_id);
        $pie_data->set_customizable(isset($_POST["pie_custom"][$loop]));

        $pie_data->set_template_id(
            esc_attr(sanitize_text_field(
                $_POST[PWP_PIE_DATA::TEMPLATE_ID][$loop]
            ))
        );

        $pie_data->set_design_id(
            esc_attr(sanitize_text_field(
                $_POST[PWP_PIE_DATA::DESIGN_ID][$loop]
            ))
        );

        $pie_data->set_color_code(
            esc_attr(sanitize_text_field(
                $_POST[PWP_PIE_DATA::COLOR_CODE][$loop]
            ))
        );

        $pie_data->set_background_id(
            esc_attr(sanitize_text_field(
                $_POST[PWP_PIE_DATA::BACKGROUND_ID][$loop]
            ))
        );

        $pie_data->set_customizable(
            (bool)esc_attr($_POST[PWP_PIE_DATA::CUSTOMIZABLE][$loop])
        );

        $pie_data->set_uses_pdf_content(
            (bool)esc_attr($_POST[PWP_PIE_DATA::USE_PDF_CONTENT][$loop])
        );

        $pie_data->update_meta_data();
    }
}
