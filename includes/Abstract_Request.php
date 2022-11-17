<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\utilities\response\I_Response;

abstract class Abstract_Request
{
    protected bool $secure = true;
    protected int $timeout = 30;
    protected int $redirects = 5;

    final public function set_secure(bool $secure = true): self
    {
        $this->secure = $secure;
        return $this;
    }
    final public function set_timeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }
    final public function set_redirects(int $redirects): self
    {
        $this->redirects = $redirects;
        return $this;
    }
    public abstract function make_request(): object;
}
