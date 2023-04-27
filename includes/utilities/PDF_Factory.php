<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\wrappers\PDF_Upload;
use Smalot\PdfParser\Parser;

class PDF_Factory
{
    public function __construct()
    {
    }

    public static function generate_from_upload(array $upload): PDF_Upload
    {
        $pdf = new PDF_Upload($upload);
        $parser = new Parser();

        $file = $parser->parseFile($pdf->get_tmp_name());
        $pageCount = count($file->getPages());
        $pageDetails = $file->getPages()[0]->getDetails();
        $width = $pageDetails['MediaBox'][2];
        $height = $pageDetails['MediaBox'][3];

        $pdf->set_page_count($pageCount);
        $pdf->set_dimensions($width, $height);

        return $pdf;
    }
}
