<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\hookables\abstracts\Abstract_Filter_Hookable;
use PWP\includes\utilities\notification\Notification;
use PWP\includes\utilities\PDF_Factory;
use PWP\includes\validation\Abstract_File_Handler;
use PWP\includes\validation\Validate_File_Dimensions;
use PWP\includes\validation\Validate_File_Errors;
use PWP\includes\validation\Validate_File_PageCount;
use PWP\includes\validation\Validate_File_Size;
use PWP\includes\validation\Validate_File_Type_Is_PDF;

class Validate_PDF_Upload extends Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_add_to_cart_validation', 'validate_pdf_upload', 10, 5);
    }

    public function validate_pdf_upload(bool $passed, int $product_id, int $quantity, int $variation_id = 0, array $variations = [])
    {
        // error_log("product: {$product_id}");
        // error_log("variation: {$variation_id}");
        // error_log("files: " . print_r($_FILES, true));

        $product = new Product_Meta_Data(wc_get_product($variation_id ?: $product_id));
        if (!$product->uses_pdf_content()) {
            // error_log("product does not require pdf upload. skipping...");
            return $passed;
        }
        if (!isset($_FILES['upload'])) {
            wc_add_notice(
                __('product requires pdf upload.', PWP_TEXT_DOMAIN),
                'error'
            );
            return false;
        }
        if (
            !isset($_FILES['upload']['error']) ||
            is_array($_FILES['upload']['error'])
        ) {
            wc_add_notice(
                __('invalid file upload parameters. Try again with a different file.', PWP_TEXT_DOMAIN),
                'error'
            );
            return false;
        }
        try {

            $pdfFactory = new PDF_Factory();
            $pdf = $pdfFactory->generate_from_upload($_FILES['upload']);

            $notification = new Notification();
            $this->validation_chain($product,)->handle($pdf, $notification);

            if (!$notification->is_success()) {
                wc_add_notice(
                    $notification ? $notification->get_errors()[0]->get_description() : __('the uploaded pdf is not valid', PWP_TEXT_DOMAIN),
                    'error'
                );
            }

            return $notification->is_success() ? $passed : false;
        } catch (\Exception $e) {
            wc_add_notice(
                __('could not process PDF upload. try again with a different file.', PWP_TEXT_DOMAIN),
                'error'
            );
            return false;
        }
    }

    /**
     * generate and return an iterator chain that validates a file
     *
     * @return Abstract_File_Handler
     */
    private function validation_chain(Product_Meta_Data $metaData): Abstract_File_Handler
    {
        $maxFileSize = (int)ini_get('upload_max_filesize') * Validate_File_Size::MB;

        $validator = new Validate_File_Type_Is_PDF();
        $validator
            ->set_next(new Validate_File_Errors())
            ->set_next(new Validate_File_Size($maxFileSize))
            ->set_next(new Validate_File_PageCount(
                $metaData->get_pdf_min_pages(),
                $metaData->get_pdf_max_pages()
            ))
            ->set_next(new Validate_File_Dimensions(
                $metaData->get_pdf_height(),
                $metaData->get_pdf_width()
            ));

        return $validator;
    }
}
