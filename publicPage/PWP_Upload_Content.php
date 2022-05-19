<?php

declare(strict_types=1);

namespace PWP\publicPage;

use setasign\Fpdi\Fpdi;
use PWP\includes\wrappers\PWP_File_Data;
use PWP\includes\PWP_Abstract_Ajax_Component;

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

    public function execute(): void
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

        $variant_id = sanitize_text_field($_POST['variant_id']);
    }

    public function execute_nopriv(): void
    {
    }
}
