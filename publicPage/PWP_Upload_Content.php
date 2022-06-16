<?php

declare(strict_types=1);

namespace PWP\publicPage;

use setasign\Fpdi\Fpdi;
use PWP\includes\wrappers\PWP_File_Data;
use PWP\includes\hookables\PWP_Abstract_Ajax_Component;
use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\notification\PWP_Notification;
use PWP\includes\utilities\PWP_Thumbnail_Generator_JPG;
use PWP\includes\validation\PWP_Abstract_File_Handler;
use PWP\includes\validation\PWP_Validate_File_Errors;
use PWP\includes\validation\PWP_Validate_File_Type;
use PWP\includes\wrappers\PWP_Product_Meta_Data;

class pwp_upload_content extends PWP_Abstract_Ajax_Component
{
    public function __construct()
    {
        parent::__construct(
            'upload_content',
            plugins_url('js/upload-content.js', __FILE__),
        );
    }

    public function callback(): void
    {
        /**
         * //TODO: implement full functionality of PPI content uploader
         * STEPS:
         * 1) check ajax nonce
         * 2) check if file upload is successful (should work with the error code from the $FILES global)
         * 3) check if PDF is valid
         * 4) Save PDF
         * 5) Generate success response with PDF details
         */

        echo ('bingo');
        return;
        $this->verify_ajax_referer();

        $file = new PWP_File_Data($_FILES['file']);
        $notification = new PWP_Notification();
        var_dump($file);

        if (!$this->validation_chain()->handle($file, $notification)) {
            wp_send_json($notification->to_array());
            return;
        }

        try {
            $pdf = new Fpdi();
            $pages = $pdf->setSourceFile($file->get_tmp_name());
            $importedPage = $pdf->importPage(1);
            $dimensions = $pdf->getTemplateSize($importedPage);
        } catch (\Throwable $error) {
            return;
        }

        $variantId = sanitize_text_field($_POST['variant_id']);
        //check if variant Id leads to a valid product
        $product = wc_get_product((int)$variantId);

        if (!$product || $product == null) {
            $error = new PWP_Error_Notice(
                __("invalid id", PWP_TEXT_DOMAIN),
                __("invalid variant Id passed, no valid product found", PWP_TEXT_DOMAIN)
            );
            wp_send_json($error->to_array());
            return;
        }

        $variant = new PWP_Product_Meta_Data($product->get_id(), $product->get_meta_data());
        $min_pages = $variant->get_pdf_min_pages();
        $max_pages = $variant->get_pdf_max_pages();

        // page & dimension validation
        if (!empty($min_pages) && $pages < $min_pages) {
            $error = new PWP_Error_Notice(
                __("too few pages", PWP_TEXT_DOMAIN),
                __("Your file has too few pages", PWP_TEXT_DOMAIN),
                array('file' => array('pages' => $pages))
            );
            wp_send_json($error->to_array());
            return;
        }
        if (!empty($max_pages) && $pages > $max_pages) {
            $error = new PWP_Error_Notice(
                __("too many pages", PWP_TEXT_DOMAIN),
                __("Your file has too many pages", PWP_TEXT_DOMAIN),
                array('file' => array('pages' => $pages))
            );
            wp_send_json($error->to_array());
            return;
        }

        $id = $this->generate_content_file_id($variantId);
        $path = $this->save_file($id, 'content');
        $this->generate_thumbnail($path, $id);
    }

    public function callback_nopriv(): void
    {
    }

    private function generate_content_file_id(string $variantId): string
    {
        return sprintf(
            "%u_%u_%s",
            get_current_user_id(),
            base64_encode((string)(microtime(true) * 1000)),
            $variantId
        );
    }

    private function save_file(string $folderName, string $fileName): string
    {
        //chmod 660
        mkdir(realpath(PWP_UPLOAD_DIR) . '/' . $folderName, 0660);
        $newFileDestination = realpath(PWP_UPLOAD_DIR) . "/{$folderName}/{$fileName}.pdf";
        move_uploaded_file($_FILES['file']['tmp_name'], $newFileDestination);
        return $newFileDestination;
    }

    private function generate_thumbnail(string $filePath, string $filename): string
    {
        $generator = new PWP_Thumbnail_Generator_JPG(-1);
        return $generator->generate($filePath, PWP_THUMBNAIL_DIR, $filename, 160);
    }

    private function validation_chain(): PWP_Abstract_File_Handler
    {
        $validator = new PWP_Validate_File_Type();
        $validator->set_next(new PWP_Validate_File_Errors());

        return $validator;
    }

    private function verify_ajax_referer(): void
    {
        if (!check_ajax_referer('pwp_upload_content_nonce', '_ajax_nonce', false)) {
            $error = new PWP_Error_Notice(
                __('nonce mismatch', PWP_TEXT_DOMAIN),
                __('Could not verify the origin of this request.', PWP_TEXT_DOMAIN)
            );
            wp_send_json($error->to_array());
            return;
        }
    }
}
