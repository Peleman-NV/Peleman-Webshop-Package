<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_File_Data;
use PWP\includes\validation\PWP_Abstract_File_Handler;
use PWP\includes\utilities\notification\PWP_I_Notification;

final class PWP_Validate_File_Type extends PWP_Abstract_File_Handler
{
    private string $type;
    public const PDF = 'pdf';

    public function __construct(string $type = self::PDF)
    {
        $this->type = $type;
    }

    public function handle(PWP_File_Data $file, PWP_I_Notification $notification): bool
    {
        $file_type = strtolower(pathinfo($file->get_name(), PATHINFO_EXTENSION));
        if ($this->type !== $file_type) {
            $notification->add_error(
                __("incorrect file extension", PWP_TEXT_DOMAIN),
                sprintf(__("Uploaded file is of the incorrect type. Expected %s.", PWP_TEXT_DOMAIN), $this->type)
            );
        }
        return $this->handle_next($file, $notification);
    }
}
