<?php

declare(strict_types=1);

namespace PWP\adminPage;

use PWP\includes\hookables\PWP_Abstract_Action_Component;
use PWP\includes\utilities\PWP_Input_Fields;
use WP_Post;

class PWP_Variable_Custom_Fields extends PWP_Abstract_Action_Component
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
        $parentId = $wc_variation->get_parent_id();

?>
        <div class="pwp-options-group">
            <h2 class="pwp-options-group-title">Fly2Data Properties - V2</h2>
            <?php

            //DO STUFF HERE
            //SKU INFO
            //f2d sku components
            PWP_Input_Fields::text_input(
                'f2d_sku_components-' . $loop,
                __('Fly2Data SKU data', PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'f2d_sku_components', true),
                array('form-row', 'form-row-first'),
                __('F2D components that make up a variation', PWP_TEXT_DOMAIN)
            );

            //f2d article code
            PWP_Input_Fields::text_input(
                'f2d_art_code-' . $loop,
                __('Fly2Data article code', PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'f2d_artcd', true),
                array('form-row', 'form-row-last'),
                __('F2D article code', PWP_TEXT_DOMAIN)
            );

            //CUSTOMIZATIONS
            //customizeable
            PWP_Input_Fields::checkbox_input(
                'customizeable-' . $loop,
                __('is customizable', PWP_TEXT_DOMAIN),
                (bool)get_post_meta($variationId, 'pie_customizable'),
                array('form-row', 'form-row-full'),
                __('Wether the product is customizeable with the Peleman Image Editor', PWP_TEXT_DOMAIN)
            );

            //template id
            PWP_Input_Fields::text_input(
                "template_id-{$loop}",
                __("PIE Template", PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'template_id', true),
                ['form-row', 'form-row-first'],
                __('TemplateID<br>E.g. M002<br>Leave empty for no customisation', PWP_TEXT_DOMAIN)

            );

            //variant code
            PWP_Input_Fields::text_input(
                "variant_code-{$loop}",
                __('PIE variant ID', PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'variant_code', true),
                ['form-row', 'form-row-last'],
                __('Variant code<br>E.g. 00201<br>Leave empty for no customisation', PWP_TEXT_DOMAIN)
            );

            //color code
            PWP_Input_Fields::color_input(
                "color_id-{$loop}",
                'template color',
                (string)get_post_meta($variationId, 'tmpl_colorcode', true),
                ['form-row', 'form-row-first'],
                'color id to use in the editor'
            );

            //background id
            PWP_Input_Fields::text_input(
                "background_id-{$loop}",
                __('Template Background ID', PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'background_id', true),
                ['form-row', 'form-row-last'],
                __('Background ID', PWP_TEXT_DOMAIN)
            );


            //PRICING & SHOP DISPLAY
            //variation add to cart label
            PWP_Input_Fields::text_input(
                "variation_add_to_cart_label-{$loop}",
                __('Custom Add To Cart Label', PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'custom_variation_add_to_cart_label', true),
                ['form-row', 'form-row-full'],
                __('Define a custom Add to cart label', PWP_TEXT_DOMAIN)
            );

            //price per page
            PWP_Input_Fields::number_input(
                "price_per_page-{$loop}",
                __("Price Per additioanl Page(Piece/sheet of paper = 2 pages)", PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'price_per_page', true),
                ['form-row', 'form-row-first'],
                __('Price per page', PWP_TEXT_DOMAIN)

            );

            //base page count
            PWP_Input_Fields::number_input(
                "base_page_count-{$loop}",
                __("Base Number of Pages", PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'base_number_of_pages', true),
                ['form-row', 'form-row-last'],
                __('Standard number of pages included in price', PWP_TEXT_DOMAIN)
            );

            //cart price
            PWP_Input_Fields::number_input(
                "cart_price-{$loop}",
                __("Unit Purchase Price", PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'cart_price', true),
                ['form-row', 'form-row-first'],
                __('These items are sold as units, not individually', PWP_TEXT_DOMAIN)
            );

            //cart units
            PWP_Input_Fields::number_input(
                "cart_units-{$loop}",
                __("Unit Amount", PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'cart_units', true),
                ['form-row', 'form-row-last'],
                __('Number of items per unit', PWP_TEXT_DOMAIN)
            );

            //unit code
            PWP_Input_Fields::text_input(
                "unit_code-{$loop}",
                __("Unit Code", PWP_TEXT_DOMAIN),
                get_post_meta($variationId, 'unit_code', true),
                ['form-row', 'form-row-first'],
                __('The Unit code of this item', PWP_TEXT_DOMAIN)
            );

            //call to order
            PWP_Input_Fields::checkbox_input(
                "call_to_order-{$loop}",
                __("Call us to order", PWP_TEXT_DOMAIN),
                (bool)get_post_meta($variationId, 'call_to_order', true),
                ['form-row', 'form-row-full'],
                __('Remove add to cart button and display "Call us to order"', PWP_TEXT_DOMAIN)
            );

            //PDF SETTINGS
            //pdf upload required
            PWP_Input_Fields::checkbox_input(
                "pdf_upload_required-{$loop}",
                __('PDF Content required', PWP_TEXT_DOMAIN),
                (bool)get_post_meta($variationId, 'pdf_upload_required', true),
                ['form-row','form-row-full'],
                __("does the product require the user to upload a PDF file?", PWP_TEXT_DOMAIN)
            )



            //END STUFF
            ?>
        </div>
<?php
    }
}
