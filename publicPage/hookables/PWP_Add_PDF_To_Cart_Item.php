<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;
use PWP\includes\services\entities\PWP_Project;

/**
 * Filter hookable class for handling PDF uploads when adding an item to the cart. If the product
 * a) requires a pdf file and b) a pdf file is uploaded,
 * this class will generate a new record of the PDF in the database and add a reference
 * to the specific item in the cart.
 */
class PWP_Add_PDF_To_Cart_Item extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_add_cart_item_data', 'add_PDF_to_cart_item', 30, 2);
    }

    public function add_PDF_to_cart_item(array $cart_item_data, int $product_id): array
    {
        //TODO: implementation
        $product = wc_get_product($product_id);
        $meta = new PWP_Product_Meta_Data($product);

        if ($meta->uses_pdf_content() && isset($_FILES['file'])) {
            $project = PWP_Project::create_new(
                0,
                $product_id,
                'my_file.pdf',
                25,
                12.00
            );
            $project->persist();
            error_log("new project ID: {$project->get_id()}");
            /** STEPS:
             * 1) create and save new entry for the item.
             * 2) use the ID of the new entry to create a directory
             * 3) store .pdf in directory
             * 4) if all has gone well, we can add the ID to the meta data of the item
             */
        }

        return $cart_item_data;
    }
}
