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

        $properties = json_decode(file_get_contents(PWP_TEMPLATES_DIR . '/PWP_Metadata.json'), true);

?>
        <div class="pwp-options-group">
            <h2 class="pwp-options-group-title">Fly2Data Properties - V2</h2>
            <?php

            foreach ($properties as $key => $property) {
                PWP_Input_Fields::create_field($key, $property['label'], $property['type'], $wc_variation->get_meta($key, true), $property['classes'], $property['description']);
            }

            //DO STUFF HERE

            ?>
        </div>
<?php
    }
}
