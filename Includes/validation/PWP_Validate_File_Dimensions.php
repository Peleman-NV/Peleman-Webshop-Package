<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_File_Data;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validate_File_Dimensions extends PWP_Abstract_File_Handler
{
    public function __construct(int $height, int $width, float $precision = .5)
    {
        parent::__construct();
    }

    public function handle(PWP_File_Data $data, PWP_I_Notification $notifictation): bool
    {
        return false;
    }
}
