<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_hookable;
use PWP\includes\utilities\PWP_Input_Fields;
use WP_Post;

class PWP_Variable_Product_Custom_Fields extends PWP_Abstract_Action_hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_product_after_variable_attributes', 'add_custom_fields', 11, 3);
    }

    /**
     * Undocumented function
     *
     * @param int $loop
     * @param array $variation_data
     * @param WP_Post $variation
     * @return void
     */
    public function add_custom_fields(int $loop, array $variation_data, WP_Post $variation): void
    {
        $variationId = $variation->ID;
        $wc_variation = wc_get_product($variationId);
        $pie_data = new PWP_PIE_Data($variationId);
        $parentId = $wc_variation->get_parent_id();

        $properties = json_decode(file_get_contents(PWP_TEMPLATES_DIR . '/PWP_Metadata.json'), true);

?>
        <div class="pwp-options-group">
            <h2 class="pwp-options-group-title">Fly2Data Properties - V2</h2>
            <h3 class="pwp_options_group_title">Peleman Image Editor Settings</h3>
            <?php

            PWP_INPUT_FIELDS::create_field(
                PWP_PIE_DATA::CUSTOMIZABLE . "[{$loop}]",
                'customizable',
                'bool',
                $pie_data->get_is_customizable(),
                ['form-row', 'form-row-full', 'checkbox', 'pie-customizable'],
                'whether the product is customizable by clients'
            );

            PWP_INPUT_FIELDS::create_field(
                PWP_PIE_DATA::USE_PDF_CONTENT . "[{$loop}]",
                'use pdf content upload',
                'bool',
                $pie_data->get_uses_pdf_content(),
                ['form-row', 'form-row-full', 'checkbox', 'pie-pdf_content'],
                'whether the product requires a PDF file for its contents'
            );

            PWP_INPUT_FIELDS::create_field(
                PWP_PIE_DATA::TEMPLATE_ID . "[{$loop}]",
                'PIE Template ID',
                'text',
                $pie_data->get_template_id(),
                ['form-row', 'form-row-first'],
            );

            PWP_INPUT_FIELDS::create_field(
                PWP_PIE_DATA::DESIGN_ID . "[{$loop}]",
                'Design ID',
                'text',
                $pie_data->get_design_id(),
                ['form-row', 'form-row-last'],
            );

            PWP_INPUT_FIELDS::create_field(
                PWP_PIE_DATA::COLOR_CODE . "[{$loop}]",
                'Color Code',
                'text',
                $pie_data->get_color_code(),
                ['form-row', 'form-row-first'],
            );

            PWP_INPUT_FIELDS::create_field(
                PWP_PIE_DATA::BACKGROUND_ID . "[{$loop}]",
                'PIE background ID',
                'text',
                $pie_data->get_background_id(),
                ['form-row', 'form-row-last'],
            );

            //DO STUFF HERE

            ?>
        </div>
<?php
    }
}
