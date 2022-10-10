<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_Product_Meta_Data;
use setasign\Fpdi\Fpdi;
use PWP\includes\wrappers\PWP_PDF_Upload;
use PWP\includes\hookables\abstracts\PWP_Abstract_Ajax_Hookable;
use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\notification\PWP_Notification;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\validation\PWP_Abstract_File_Handler;
use PWP\includes\validation\PWP_Validate_File_Dimensions;
use PWP\includes\validation\PWP_Validate_File_Errors;
use PWP\includes\validation\PWP_Validate_File_PageCount;
use PWP\includes\validation\PWP_Validate_File_Size;
use PWP\includes\validation\PWP_Validate_File_Type_Is_PDF;

class PWP_Ajax_Upload_PDF extends PWP_Abstract_Ajax_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'PWP_Upload_PDF',
            plugins_url('Peleman-Webshop-Package/publicPage/js/upload-content.js'),
            6,
        );
    }

    public function callback(): void
    {
        /**
         * //TODO: implement full functionality of PPI content uploader
         * STEPS:
         * 1) check ajax nonce - using check ajax_referer method
         * 2) check if file upload is successful (should work with the error code from the $FILES global)
         * 3) check if PDF is valid
         * 4) Save PDF
         *  - how do we save pdfs and avoid file conflicts without ending up with tons of uploads cluttering the uploads folder?
         * 5) Generate success response with PDF details
         */

        /** 1) */
        $this->validate_request_nonce($_REQUEST['nonce']);

        /** 2) */
        $file = new PWP_PDF_Upload($_FILES['file']);
        $productId = (int)sanitize_text_field($_REQUEST['variant_id'] ?: $_REQUEST['product_id']);
        $product = wc_get_product($productId);
        $productMeta = new PWP_Product_Meta_Data($product);

        //check if product Id leads to a valid product
        if (!$product) {
            $this->send_json_error(
                'invalid product id',
                'no product with this product ID passed',
                200,
                array('id' => $productId)
            );
        }

        $notification = new PWP_Notification();
        error_log(print_r($_REQUEST, true));
        error_log(print_r($file, true));

        /** 3) */
        try {
            $pdf = new Fpdi();
            $file->set_page_count($pdf->setSourceFile($file->get_tmp_name()));
            $importedPage = $pdf->importPage(1);

            $dimensions = $pdf->getTemplateSize($importedPage);
            $file->set_dimensions($dimensions['width'], $dimensions['height']);
        } catch (\Throwable $error) {
            $this->send_json_error(
                $error->getMessage(),
                '',
                200
            );
        }
        if (!$this->validation_chain($productMeta)->handle($file, $notification)) {
            $error = $notification->get_errors()[0];
            $this->send_json_error(
                $error->get_message(),
                $error->get_description(),
                420
            );
        }

        /** 4) */
        $id = $this->generate_content_file_id($productId);
        // $path = $this->save_file($id, 'content');
        // error_log($path);
        // $this->generate_thumbnail($path, $id, 160);


        //calculate prices

        $priceVatExcl = wc_get_price_excluding_tax($product);
        $priceVatIncl = wc_get_price_including_tax($product);
        $pricePerPage = $productMeta->get_price_per_page();

        $priceWithPages = $pricePerPage * $file->get_page_count() + $priceVatExcl;
        $this->send_json_success(
            'success',
            'success! you have successfully reached the end of the operation chain!',
            200,
            array(
                'file' => array(
                    'price_vat_incl'    => $priceWithPages,
                    'name'              => 'bleep',
                    'content_file_id'   => '123456789bbb',
                )
            )
        );
    }

    public function callback_nopriv(): void
    {
        $this->callback();
    }

    private function generate_content_file_id(int $productId): string
    {
        return sprintf(
            "%u_%u_%s",
            get_current_user_id(),
            base64_encode((string)(microtime(true) * 1000)),
            $productId
        );
    }

    private function save_file(string $folderName, string $fileName): string
    {
        //chmod 660
        $targetDirectory = PWP_UPLOAD_DIR . $folderName;
        error_log($targetDirectory);
        mkdir($targetDirectory, 0660, true);
        $newFileDestination = realpath(PWP_UPLOAD_DIR) . "/{$folderName}/{$fileName}.pdf";
        move_uploaded_file($_FILES['file']['tmp_name'], $newFileDestination);
        return $newFileDestination;
    }

    private function generate_thumbnail(string $filePath, string $filename, int $targetWidth): string
    {
        if (!class_exists('Imagick')) {
            wp_send_json_error("Imagick not active!");
        }
        // if (!WP_Image_Editor_Imagick::test())
        //     wp_send_json_error("imagick not supported", 420);
        $im = new \Imagick();
        $im->readImage($filePath . '[0]');
        $im->setImageFormat('jpg');
        $im->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
        $im->setCompressionQuality(25);
        $im->scaleImage(150, 0);
        $im->writeImage(PWP_THUMBNAIL_DIR . '/' . $filename);
        return PWP_THUMBNAIL_DIR . '/' . $filename;
        // $generator = new PWP_Thumbnail_Generator_JPG(-1);
        // return $generator->generate($filePath . '[0]', PWP_THUMBNAIL_DIR, $filename, 160);
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

    private function validate_request_nonce(string $nonce): void
    {
        if (!$this->verify_nonce($nonce)) {
            $error = new PWP_Error_Notice(
                'nonce mismatch',
                'could not verify origin of request',
            );
            wp_send_json_error($error->to_array(), 401);
        }
    }

    private function send_json_error(string $message, string $description, int $httpCode = 400, array $data = []): void
    {
        $error = new PWP_Error_Notice(
            __($message, PWP_TEXT_DOMAIN),
            __($description, PWP_TEXT_DOMAIN),
            $data
        );
        wp_send_json_error($error->to_array(), $httpCode);
    }

    private function send_json_success(string $message, string $description, int $httpCode = 200, array $data = []): void
    {
        $success = new PWP_Success_Notice(
            __($message, PWP_TEXT_DOMAIN),
            __($description, PWP_TEXT_DOMAIN),
            $data
        );
        wp_send_json_success($success->to_array(), $httpCode);
    }
}
