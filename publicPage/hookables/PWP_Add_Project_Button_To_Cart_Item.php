<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_IMAXEL_Data;
use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\includes\services\ImaxelService;

class PWP_Add_Project_Button_To_Cart extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_after_cart_item_name', 'render_project_button', 10, 2);
    }

    public function render_project_button(array $cart_item, string $cart_item_key)
    {
        //get project data from cart item
        if (!$cart_item['_project_id'] || !$cart_item['_editor_id'])
            return;


        $project_id = $cart_item['_project_id'];
        $editor_id = $cart_item['_editor_id'];
        $project_url = '';

        switch ($editor_id) {
            case (PWP_PIE_Data::MY_EDITOR):
                $project_url = $cart_item['_editor_url'];
                break;
            case PWP_IMAXEL_Data::MY_EDITOR:
                $service = new ImaxelService();
                $project_url = $service->get_editor_url($project_id, wc_get_cart_url(), 'en', wc_get_cart_url());
                break;
            default:
                return;
        }
?>
        <a href="<?= wc_clean($cart_item['_project_url']) ?>" class="button">edit project</a>
<?php

    }
}

// ( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key )