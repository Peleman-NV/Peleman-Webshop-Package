<?php

declare(strict_types=1);

namespace PWP\adminPage;

use WP_Post;
use PWP\includes\utilities\PWP_Input_Fields;
use PWP\includes\hookables\PWP_Abstract_Action_Component;

class PWP_Parent_Custom_Fields extends PWP_Abstract_Action_Component
{
    public function __construct()
    {
        parent::__construct('woocommerce_product_options_general_product_data', 'add_custom_fields', 11, 3);
    }

    /**
     * Undocumented function
     *
     * @param int $loop
     * @param array $variation_data
     * @param WP_Post $variation
     * @return void
     */
    public function add_custom_fields(): void
    {
?>
        <div class="pwp-options-group">
            <h2 class="pwp-options-group-title">Fly2Data Properties - V2</h2>
            <?php

            //DO STUFF HERE

            //END STUFF
            ?>
        </div>
<?php
    }
}
