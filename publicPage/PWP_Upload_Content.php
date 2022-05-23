<?php

declare(strict_types=1);

namespace PWP\publicPage;

use Imagick;
use setasign\Fpdi\Fpdi;
use PWP\includes\wrappers\PWP_File_Data;
use PWP\includes\hookables\PWP_Abstract_Ajax_Component;

class pwp_upload_content extends PWP_Abstract_Ajax_Component
{
    public function __construct()
    {
        parent::__construct(
            'pwp_upload_content_nonce',
            'pwp-ajax-upload',
            'ajax_upload_content',
            plugins_url('js/upload-content.js', __FILE__)
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

        if (!check_ajax_referer('pwp_upload_content_nonce', '_ajax_nonce', false)) {
            return;
        }
        $file = new PWP_File_Data($_FILES['file']);
        var_dump($file);

        try {
            $pdf = new Fpdi();
            $pages = $pdf->setSourceFile($file->get_tmp_name());
            $importedPage = $pdf->importPage(1);
            $dimensions = $pdf->getTemplateSize($importedPage);
        } catch (\Throwable $error) {
            return;
        }

        $variantId = sanitize_text_field($_POST['variant_id']);

        // page & dimension validation
        $variant = $this->getVariantContentParameters($variantId);
        if ($variant['min_pages'] != "" && $pages < $variant['min_pages']) {
            $response['status'] = 'error';
            $response['file']['pages'] = $pages;
            $response['message'] = __("Your file has too few pages.", PPI_TEXT_DOMAIN);
        }
        if ($variant['max_pages'] != "" && $pages > $variant['max_pages']) {
            $response['status'] = 'error';
            $response['file']['pages'] = $pages;
            $response['message'] = __("Your file has too many pages.", PPI_TEXT_DOMAIN);
        }


        $id = $this->generate_content_file_id($variantId);
        $path = $this->save_file($id);
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

    private function save_file(string $contentFileId): string
    {
        mkdir(realpath(PPI_UPLOAD_DIR) . '/' . $contentFileId);
        $newFilenameWithPath = realpath(PPI_UPLOAD_DIR) . '/' . $contentFileId . '/content.pdf';
        move_uploaded_file($_FILES['file']['tmp_name'], $newFilenameWithPath);
        return realpath($newFilenameWithPath);
    }

    private function generate_thumbnail(string $filePath, string $filename): void
    {
        $imagick = new Imagick();
        $imagick->readImage($filePath . '[0]');
        $imagick->setImageFormat('jpg');
        $thumbnailWithPath = realpath(PPI_THUMBNAIL_DIR) . '/' . $filename . '.jpg';
        $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_DEACTIVATE);
        $imagick->setCompressionQuality(25);
        $imagick->scaleImage(150, 0);
        $imagick->writeImage($thumbnailWithPath);
    }
}
