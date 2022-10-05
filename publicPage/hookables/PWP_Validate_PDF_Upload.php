<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;
use PWP\includes\utilities\notification\PWP_Notification;
use PWP\includes\utilities\PWP_PDF_Factory;
use PWP\includes\validation\PWP_Abstract_File_Handler;
use PWP\includes\validation\PWP_Validate_File_Dimensions;
use PWP\includes\validation\PWP_Validate_File_Errors;
use PWP\includes\validation\PWP_Validate_File_PageCount;
use PWP\includes\validation\PWP_Validate_File_Size;
use PWP\includes\validation\PWP_Validate_File_Type_Is_PDF;

class PWP_Validate_PDF_Upload extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_add_to_cart_validation', 'validate_pdf_upload', 1, 5);
    }

    public function validate_pdf_upload(bool $passed, int $product_id, int $quantity, int $variation_id = 0, int $variations = 0)
    {
        $product = new PWP_Product_Meta_Data(wc_get_product($variation_id ?: $product_id));
        if (!$product->uses_pdf_content()) return true;
        if (
            !isset($_FILES['pdf_upload']['error']) ||
            is_array($_FILES['pdf_upload']['error'])
        ) {
            wc_add_notice(
                __('invalid file upload parameters. Try again with a different file.', PWP_TEXT_DOMAIN),
                'error'
            );
            return false;
        }
        try {

            $pdfFactory = new PWP_PDF_Factory();
            $pdf = $pdfFactory->generate_from_upload($_FILES['pdf_upload']);

            $notification = new PWP_Notification();
            $this->validation_chain($product,)->handle($pdf, $notification);

            if (!$notification->is_success()) {
                wc_add_notice(
                    $notification ? $notification->get_errors()[0]->get_description() : __('the uploaded pdf is not valid', PWP_TEXT_DOMAIN),
                    'error'
                );
            }

            return $notification->is_success();
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
     * @return PWP_Abstract_File_Handler
     */
    private function validation_chain(PWP_Product_Meta_Data $metaData): PWP_Abstract_File_Handler
    {
        $maxFileSize = (int)ini_get('upload_max_filesize') * PWP_Validate_File_Size::MB;

        $validator = new PWP_Validate_File_Type_Is_PDF();
        $validator
            ->set_next(new PWP_Validate_File_Errors())
            ->set_next(new PWP_Validate_File_Size($maxFileSize))
            ->set_next(new PWP_Validate_File_PageCount(
                $metaData->get_pdf_min_pages(),
                $metaData->get_pdf_max_pages()
            ))
            ->set_next(new PWP_Validate_File_Dimensions(
                $metaData->get_pdf_height(),
                $metaData->get_pdf_width()
            ));

        return $validator;
    }
}
