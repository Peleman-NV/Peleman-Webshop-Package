<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\PWP_Term_Handler;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\wrappers\PWP_Term_Data;

final class PWP_Create_Term_Command implements PWP_I_Command
{
    private PWP_Term_Handler $handler;
    private string $slug;
    private PWP_Term_Data $creationData;

    public function __construct(PWP_Term_Handler $handler, string $slug, PWP_Term_Data $data)
    {
        $this->handler = $handler;
        $this->slug = $slug;
        $this->creationData = $data;
    }

    public function do_action(): PWP_I_Response
    {
        // $this->handler->create_item($this->updateData);
        return new PWP_Response("not implemented");
    }

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Response("not implemented");
    }
}
