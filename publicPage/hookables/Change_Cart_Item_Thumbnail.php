<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Keys;
use PWP\includes\hookables\abstracts\Abstract_Filter_Hookable;

class Change_Cart_Item_Thumbnail extends Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_cart_item_thumbnail', 'pwp_override_cart_item_thumbnail', 15, 3);
    }

    public function pwp_override_cart_item_thumbnail(string $image, array $cart_item, $cart_item_key): string
    {
        if ($cart_item['_project_id']) {
            $projectId = $cart_item['_project_id'];
            $product = $cart_item['data'];

            if (boolval($product->get_meta(Keys::OVERRIDE_CART_THUMBNAIL))) {

                $image = sprintf(
                    '<img width="450" height="450" src="%s" class="woocommerce-placeholder wp-post-image" alt="%s" decoding="async" loading="lazy" sizes="(max-width: 450px) 100vw, 450px" />',
                    'https://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Reflection_in_a_soap_bubble_edit.jpg/800px-Reflection_in_a_soap_bubble_edit.jpg',
                    'project thumbnail'
                );
                // error_log(print_r($image, true));
                // error_log(print_r($cart_item, true));
                // error_log(print_r($cart_item_key, true));
            }
        }
        return $image;
    }
}
