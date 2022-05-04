<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;

final class PWP_Read_Term_Command implements PWP_I_Command

{
    public function __construct(PWP_Term_SVC $service, array $args = [])
    {
        $this->service = $service;
        $this->args = $args;
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
