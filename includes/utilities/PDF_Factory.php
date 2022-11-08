<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\wrappers\PDF_Upload;
use setasign\Fpdi\Fpdi;

class PDF_Factory
{
    public function __construct()
    {
    }

    public static function generate_from_upload(array $upload): PDF_Upload
    {
        $pdf = new PDF_Upload($upload);

        $fpdi = new Fpdi();

        $pageCount = $fpdi->setSourceFile($pdf->get_tmp_name());
        $importedPage = $fpdi->importPage(1);
        $dimensions = $fpdi->getTemplateSize($importedPage);

        $pdf->set_page_count($pageCount);
        $pdf->set_dimensions($dimensions['width'], $dimensions['height']);


        return $pdf;
    }
}
