<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_File_Data;
use PWP\includes\validation\PWP_Abstract_File_Handler;
use PWP\includes\utilities\notification\PWP_I_Notification;

final class PWP_Validate_File_Size extends PWP_Abstract_File_Handler
{

    /** bytes in a kilobyte */
    public const KB = 1024;
    /** bytes in a megabyte */
    public const MB = 1048576;
    /** bytes in a gigabyte */
    public const GB = 1073741824;

    private int $maxFileSize;

    /**
     * validator for maximum file size.
     *
     * @param integer $maxFileSize maximum file size in bytes
     */
    public function __construct(int $maxFileSize)
    {
        $this->maxFileSize = $maxFileSize;
    }

    public function handle(PWP_File_Data $file, ?PWP_I_Notification $notification = null): bool
    {
        if ($file->get_size() <= $this->maxFileSize)
            return $this->handle_next($file, $notification);

        $notification->add_error('file too large', 'The file is too large. Please upload a file smaller than the maximum upload size.');
        return false;
        // return ($file->get_size() <= $this->maxFileSize) ? $this->handle_next($file, $notification) : false;
    }
}
