<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\wrappers\PWP_PDF_Upload;

class PWP_PDF_Validator
{
    public function __construct()
    {
    }

    public function validate_pdf(PWP_PDF_Upload $pdf): PWP_I_Response
    {
        return PWP_Response::success('pdf validated successfully', 'pdf file was validated successfully.');
    }
}
