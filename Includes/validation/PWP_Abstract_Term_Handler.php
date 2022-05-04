<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;

abstract class PWP_Abstract_Term_Handler
{
    private ?PWP_Abstract_Term_Handler $next;

    public function __construct()
    {
        $this->next = null;
    }

    /**
     * set next link in chain of responsibility
     *
     * @param PWP_Abstract_Term_Handler $next
     * @return PWP_Abstract_Term_Handler
     */
    final public function set_next(PWP_Abstract_Term_Handler $next): PWP_Abstract_Term_Handler
    {
        $this->next = $next;
        return $this->next;
    }

    /**
     * main validation handler function
     * 
     * Should return `false` if validation is invalid, `true` if validation is valid or chain has reached its final link.
     *
     * @param PWP_Term_Data $request
     * @return boolean
     */
    abstract public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): bool;

    /**
     * internal helper function. Will call the next chain in the link, and return `true` if no next link is set, indicating the end of the chain. 
     *
     * @param PWP_Term_Data $request
     * @return boolean
     */
    final protected function handle_next(PWP_Term_SVC $service, PWP_Term_Data $request): bool
    {
        return is_null($this->next) ? true : $this->next->handle($service, $request);
    }
}
