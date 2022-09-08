<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\response\PWP_I_Response_Component;

interface PWP_I_Command
{
    public function do_action(): PWP_I_Response_Component;
    // public function undo_action(): PWP_I_Response_Component;
}
