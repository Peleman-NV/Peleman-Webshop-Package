<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\utilities\notification\I_Notification;
use PWP\includes\wrappers\PDF_Upload;

class Validate_File_Errors extends Abstract_File_Handler
{
    final public function handle(PDF_Upload $file, ?I_Notification $notification = null): bool
    {
        if (!empty($file->get_error())) {
            $notification->add_error(
                __("file upload error", PWP_TEXT_DOMAIN),
                $this->code_to_message($file->get_error())
            );
        }
        return $this->handle_next($file, $notification);
    }

    private function code_to_message(int $code): string
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                return  __("The uploaded file exceeds the upload maximum file size directive.", PWP_TEXT_DOMAIN);
            case UPLOAD_ERR_FORM_SIZE:
                return __("The uploaded file exceeds the maximum file size specified in the HTML form.", PWP_TEXT_DOMAIN);
            case UPLOAD_ERR_PARTIAL:
                return __("The file was only partially uploaded.", PWP_TEXT_DOMAIN);
            case UPLOAD_ERR_NO_FILE:
                return __("No file was uploaded.", PWP_TEXT_DOMAIN);
            case UPLOAD_ERR_NO_TMP_DIR:
                return __("Temporary folder missing.", PWP_TEXT_DOMAIN);
            case UPLOAD_ERR_CANT_WRITE:
                return __("Failed to write file to disk.", PWP_TEXT_DOMAIN);
            case UPLOAD_ERR_EXTENSION:
                return __("File upload stopped by extension.", PWP_TEXT_DOMAIN);
            default:
                return __("Unknown upload error.", PWP_TEXT_DOMAIN);
        }
    }
}
