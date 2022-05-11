<?php

declare(strict_types=1);

namespace PWP\includes\utilities\notification;

interface PWP_I_Notification_Message
{
    public function get_message(): string;
    public function get_description(): string;
}
