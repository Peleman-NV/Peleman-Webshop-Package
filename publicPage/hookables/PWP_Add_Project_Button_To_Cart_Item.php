<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_PIE_Project;
use PWP\includes\editor\PWP_Product_IMAXEL_Data;
use PWP\includes\editor\PWP_Product_PIE_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\includes\services\ImaxelService;

class PWP_Add_Project_Button_To_Cart_Item extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_after_cart_item_name', 'render_project_button', 10, 2);
    }

    public function render_project_button(array $cart_item, string $cart_item_key)
    {
        //get project data from cart item
        if (!isset($cart_item['_project_id']) || !isset($cart_item['_editor_id']))
            return;

        $editor_id = $cart_item['_editor_id'];
        $project_id = $cart_item['_project_id'];
        $project_url = '';

        switch ($editor_id) {
            case (PWP_Product_PIE_Data::MY_EDITOR):
                $project_url = $this->get_PIE_Project_Url($cart_item, $project_id);
                break;
            case (PWP_Product_IMAXEL_Data::MY_EDITOR):
                $project_url = $this->get_IMAXEL_project_url($cart_item, $project_id);
                break;
            default:
                return;
        }
?>
        <span>
            <p>
                <a href="<?= $project_url ?>" class="pwp_editor_button"><?= esc_html__('edit'); ?></a>
            </p>
        </span>
<?php
    }

    private function get_PIE_Project_Url(array $cart_item, string $project_id): string
    {
        $variant_id = $cart_item['variation_id'];
        $product_id = $cart_item['product_id'];
        $data = new PWP_Product_PIE_Data(wc_get_product($variant_id ?: $product_id));
        $project = new PWP_PIE_Project($data, $project_id);
        return wc_clean($project->get_project_editor_url(true));
    }

    private function get_IMAXEL_project_url(array $cart_item, string $project_id): string
    {
        $service = new ImaxelService();
        return $service->get_editor_url(
            $project_id,
            wc_get_cart_url(),
            //get site language
            explode('-', get_locale())[0],
            wc_get_cart_url()
        );
    }
}
