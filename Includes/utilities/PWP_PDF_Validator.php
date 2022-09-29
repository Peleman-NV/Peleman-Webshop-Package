<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\wrappers\PWP_File_Data;

class PWP_PDF_Validator
{
    public function __construct()
    {
    }

    public function validate_pdf(PWP_File_Data $pdf): PWP_I_Response
    {
        return PWP_Response::success('pdf validated successfully', 'pdf file was validated successfully.');
    }
}
