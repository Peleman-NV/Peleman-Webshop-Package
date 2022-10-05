<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use Exception;
use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;
use PWP\includes\services\entities\PWP_Project;
use PWP\includes\utilities\PWP_PDF_Factory;
use PWP\includes\wrappers\PWP_File_Data;
use setasign\Fpdi\Fpdi;

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
        $meta = new PWP_Product_Meta_Data(wc_get_product($product_id));
        error_log($meta->uses_pdf_content() ? 'foo' : 'bar');
        error_log(print_r($_FILES, true));

        $fileArr = $_FILES['pdf_upload'];

        try {

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
         
            if ($meta->uses_pdf_content() && 'application/pdf' == $fileArr['type'] && 0 === $fileArr['error']) {

                if (4 == $fileArr['error']) {
                    wp_die('something went wrong with the file upload', 'upload failure');
                }

                $pdf = PWP_PDF_Factory::generate_from_upload($fileArr);

                $filename = $pdf->get_name();
                $project = PWP_Project::create_new(
                    get_current_user_id(),
                    $product_id,
                    $filename,
                    $pdf->get_page_count(),
                    12.00
                );
                $project->persist();
                $id = $project->get_id();
                $uploadFile = realpath(PWP_UPLOAD_DIR) . "/{$id}/" . basename($filename);
                error_log("new project ID: {$id}");
                error_log("new file destination: {$uploadFile}");
                /** STEPS:
                 * 1) create and save new entry for the item.
                 * 2) use the ID of the new entry to create a directory
                 * 3) store .pdf in directory
                 * 4) if all has gone well, we can add the ID to the meta data of the item
                 */

                if (!realpath(PWP_UPLOAD_DIR . "/{$id}")) {
                    mkdir(realpath(PWP_UPLOAD_DIR) . "/{$id}", 0660);
                }
                if (move_uploaded_file($pdf->get_tmp_name(), $uploadFile)) {
                }
            }

            $cart_item_data['_pdf_data'] = array(
                'id'        => $id,
                'pdf_name'  => $filename
            );

            return $cart_item_data;
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
