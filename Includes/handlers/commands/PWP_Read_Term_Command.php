<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\validation\PWP_Validation_Handler;
use PWP\includes\validation\PWP_Abstract_Term_Handler;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

final class PWP_Read_Term_Command implements PWP_I_Command
{
    protected PWP_Abstract_Term_Handler $handler;
    protected PWP_Term_SVC $service;
    protected array $args;

    public function __construct(PWP_Term_SVC $service, array $args = [])
    {
        $this->service = $service;
        $this->args = $args;

        $this->handler = new PWP_Validation_Handler();
    }

    public function do_action(): PWP_I_Response
    {
        $this->service->enable_sitepress_get_term_filter();
        $items = $this->service->get_items($this->args);
        $itemArray = array_map(function ($e) {
            return $e->data;
        }, $items);
        $this->service->disable_sitepress_get_term_filter();

        return new PWP_Response("Terms", array("results" => count($itemArray), 'items' => $itemArray));
    }

    public function undo_action(): PWP_I_Response
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }
}
