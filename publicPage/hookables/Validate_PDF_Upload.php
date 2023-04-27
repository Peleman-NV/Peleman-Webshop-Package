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
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;

/**
 * Validates pdf upload by customer in the woocommerce product validation chain
 */
class Validate_PDF_Upload extends Abstract_Filter_Hookable
{
    private string $key;
    public function __construct()
    {
        parent::__construct('woocommerce_add_to_cart_validation', 'validate_pdf_upload', 10, 5);
        $this->key = "upload";
    }

    public function validate_pdf_upload(bool $passed, int $product_id, int $quantity, int $variation_id = 0, array $variations = []): bool
    {
        $product = new Product_Meta_Data(wc_get_product($variation_id ?: $product_id));

        if (!$product->uses_pdf_content()) return $passed;

        if (!isset($_FILES[$this->key])) {
            wc_add_notice(
                __('product requires PDF upload.', 'Peleman-Webshop-Package'),
                'error'
            );
            return false;
        }

        if (!isset($_FILES[$this->key]['error']) || is_array($_FILES[$this->key]['error'])) {
            wc_add_notice(
                __('invalid file upload parameters. Try again with a different file.', 'Peleman-Webshop-Package'),
                'error'
            );
            return false;
        }

        try {

            $pdfFactory = new PDF_Factory();
            $pdf = $pdfFactory->generate_from_upload($_FILES[$this->key]);

            $notification = new Notification();
            $this->validation_chain($product,)->handle($pdf, $notification);

            if (!$notification->is_success()) {
                wc_add_notice(
                    $notification->get_errors()[0]->get_description() ?: __('the uploaded pdf is not valid', 'Peleman-Webshop-Package'),
                    'error'
                );
            }

            return $notification->is_success() ? $passed : false;
        } catch (CrossReferenceException $e) {
            Error_log((string)$e);
            wc_add_notice(
                __('PDF file has been compressed with an unsupported compression method. Please try again with an uncompressed PDF or PDF up to version 1.4.', 'Peleman-Webshop-Package'),
                'error'
            );
            return false;
        } catch (\Exception $e) {
            Error_log((string)$e);
            wc_add_notice(
                __('Could not process PDF upload due to an unexpected error. Please try again with a different file.', 'Peleman-Webshop-Package'),
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
                $metaData->get_pdf_width(),
                5.0
            ));

        return $validator;
    }
}
