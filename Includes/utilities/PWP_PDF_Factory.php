<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\wrappers\PWP_File_Data;
use setasign\Fpdi\Fpdi;

class PWP_PDF_Factory
{
    public function __construct()
    {
    }

    public static function generate_from_upload(array $upload): PWP_File_Data
    {
        $pdf = new PWP_File_Data($upload);

        $fpdi = new Fpdi();

        $pageCount = $fpdi->setSourceFile($pdf->get_tmp_name());
        $importedPage = $fpdi->importPage(1);
        $dimensions = $fpdi->getTemplateSize($importedPage);

        $pdf->set_page_count($pageCount);
        $pdf->set_dimensions($dimensions['width'], $dimensions['height']);


        return $pdf;
    }
}
