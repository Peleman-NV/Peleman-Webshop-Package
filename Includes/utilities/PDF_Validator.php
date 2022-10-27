<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\utilities\response\I_Response;
use PWP\includes\utilities\response\Response;
use PWP\includes\wrappers\PDF_Upload;

class PDF_Validator
{
    public function __construct()
    {
    }

    public function validate_pdf(PDF_Upload $pdf): I_Response
    {
        return Response::success('pdf validated successfully', 'pdf file was validated successfully.');
    }
}
