<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\editor\PWP_IMAXEL_Data;
use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_hookable;
use WP_Post;

class PWP_Save_Parent_Custom_Fields extends PWP_Abstract_Action_hookable
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
        if (!isset($product)) {
            error_log("tried to save parameters for product with id {$postId}, but something went wrong");
            return;
        }

        $product->update_meta_data(
            'customizable_product',
            isset($_POST['customizable_product']) ? 'yes' : 'no'
        );
        $product->update_meta_data(
            'custom_add_to_cart_label',
            sanitize_text_field($_POST['custom_add_to_cart_label']),
        );

        $product->save_meta_data();
    }
}
