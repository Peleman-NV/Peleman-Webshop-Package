<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\utilities\response\PWP_I_Response;

abstract class PWP_Abstract_Request
{
    protected bool $secure = true;
    protected int $timeout = 30;

    public function set_secure(bool $secure = true): self
    {
        $this->secure = $secure;
        return $this;
    }
    public function set_timeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }
    public abstract function make_request(): PWP_I_Response;
}
