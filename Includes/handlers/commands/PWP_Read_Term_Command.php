<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\validation\PWP_Validation_Handler;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\validation\PWP_Abstract_Term_Handler;
use PWP\includes\utilities\notification\PWP_Notification;
use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\utilities\response\PWP_I_Response;

final class PWP_Read_Term_Command implements PWP_I_Command
{
    protected PWP_Abstract_Term_Handler $handler;
    protected PWP_Term_SVC $service;
    protected array $args;

    public function __construct(PWP_Term_SVC $service, array $args = [])
    {
        $this->service = $service;
        $this->args = $args;

        $this->handler = new PWP_Validation_Handler($this->service);
    }

    public function do_action(): PWP_I_Response
    {
        $this->service->enable_sitepress_get_term_filter();
        $items = $this->service->get_items($this->args);
        $itemArray = $this->get_data_from_items($items);
        $this->service->disable_sitepress_get_term_filter();

        // return PWP_Response::success("Terms", array("results" => count($itemArray), 'items' => $itemArray));
        return new PWP_Success_Notice("Terms", $this->service->get_taxonomy_name(), array(
            'results' => count($itemArray),
            'items' => $itemArray
        ));
    }

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Error_Notice("method not implemented", "method " . __METHOD__ . " not implemented");
    }

    private function get_data_from_items(array $items): array
    {
        return array_map(function ($e) {
            return (array)$e->data;
        }, $items);
    }
}
