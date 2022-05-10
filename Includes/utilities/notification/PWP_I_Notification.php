<?php

declare(strict_types=1);

namespace PWP\includes\utilities\notification;

interface PWP_I_Notification
{
    public function has_errors(): bool;

    public function add_error(string $error, string $description, \Exception $cause = null): self;

    public function get_errors(): array;
}
