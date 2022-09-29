<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\notification\PWP_I_Notice;

interface PWP_I_Command
{
    public function do_action(): PWP_I_Notice;
}
