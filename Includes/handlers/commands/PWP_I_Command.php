<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\response\PWP_I_Response;

interface PWP_I_Command
{
    public function do_action(): PWP_I_Response;
    public function undo_action(): PWP_I_Response;
}
