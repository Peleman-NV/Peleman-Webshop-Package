<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\PWP_Term_Handler;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;

final class PWP_Delete_Term_Command implements PWP_I_Command
{
    private PWP_Term_Handler $handler;
    private string $slug;

    public function __construct(PWP_Term_Handler $handler, string $slug)
    {
        $this->handler = $handler;
        $this->slug = $slug;
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
