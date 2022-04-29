<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\PWP_Term_Handler;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;

class PWP_Update_Term_Command implements PWP_I_Command
{
    private PWP_Term_Handler $handler;
    private string $slug;
    private array $updateData;

    public function __construct(PWP_Term_Handler $handler, string $slug, array $updateData = [])
    {
        $this->handler = $handler;
        $this->slug = $slug;
        $this->updateData = $updateData;
    }

    public function do_action(): PWP_I_Response
    {
        return new PWP_Response("not implemented");
    }

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Response("not implemented");
    }
}
