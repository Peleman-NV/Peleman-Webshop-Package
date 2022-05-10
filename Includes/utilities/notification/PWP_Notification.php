<?php

declare(strict_types=1);

namespace PWP\includes\utilities\notification;

class PWP_Notification implements PWP_I_Notification
{
    private array $errors;

    public function add_error(string $error, string $description, \Exception $cause = null): PWP_I_Notification
    {
        $this->errors[] = new PWP_Error($error, $description, $cause);
        return $this;
    }

    public function has_errors(): bool
    {
        return 0 < count($this->errors);
    }

    public function get_errors(): array

    {
        $notes = array_map(function ($entry) {
            return $entry->get_message;
        }, $this->errors);

        return $notes;
    }
}
