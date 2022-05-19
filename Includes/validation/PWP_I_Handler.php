<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\wrappers\PWP_Component;

interface PWP_I_Handler
{
    public function set_next(PWP_I_Handler $next): PWP_I_Handler;

    public function handle(PWP_Component $data, PWP_I_Notification $notification): bool;
    public function handle_next(PWP_Component $data, PWP_I_Notification $notification): bool;
}
