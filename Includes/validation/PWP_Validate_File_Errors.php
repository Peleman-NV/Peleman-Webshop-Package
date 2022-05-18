<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\wrappers\PWP_File_Data;

class PWP_Validate_File_Errors extends PWP_Abstract_File_Handler
{
    final public function handle(PWP_File_Data $file, PWP_I_Notification $notification): bool
    {
        if (!empty($file->get_error())) {
            $notification->add_error(
                __("upload error", PWP_TEXT_DOMAIN),
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
