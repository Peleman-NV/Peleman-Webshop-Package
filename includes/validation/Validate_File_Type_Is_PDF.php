<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PDF_Upload;
use PWP\includes\validation\Abstract_File_Handler;
use PWP\includes\utilities\notification\I_Notification;

final class Validate_File_Type_Is_PDF extends Abstract_File_Handler
{
    private string $type;
    public const PDF = 'pdf';

    public function __construct(string $type = self::PDF)
    {
        $this->type = $type;
    }

    public function handle(PDF_Upload $file, ?I_Notification $notification = null): bool
    {
        $extension = strtolower(pathinfo($file->get_name(), PATHINFO_EXTENSION));
        $type = $file->get_type();
        
        if ($this->type !== $extension || $type != 'application/pdf') {
            $notification->add_error(
                __("incorrect file extension", Peleman-Webshop-Package),
                sprintf(__("Uploaded file is of the incorrect type. Expected %s.", Peleman-Webshop-Package), $this->type)
            );
        }
        return $this->handle_next($file, $notification);
    }
}
