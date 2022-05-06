<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\validation\PWP_Abstract_Term_Handler;
use PWP\includes\validation\PWP_Validate_Term_Slug_Exists;
use PWP\includes\validation\PWP_Validation_Handler;
use PWP\includes\wrappers\PWP_Term_Data;
use WP_Term;

final class PWP_Delete_Term_Command implements PWP_I_Command
{
    private PWP_Term_SVC $service;
    private string $slug;

    private PWP_Abstract_Term_Handler $handler;

    public function __construct(PWP_Term_SVC $service, string $slug)
    {
        $this->service = $service;
        $this->slug = $slug;

        $this->handler = new PWP_Validation_Handler();
        $this->handler->set_next(new PWP_Validate_Term_Slug_Exists());
    }

    public function do_action(): PWP_I_Response
    {
        $response = $this->handler->handle($this->service, new PWP_Term_Data(['slug' => $this->slug]));
        if (!$response->is_success()) {
            return $response;
        }
        
        if ($this->delete_term()) {
            return  PWP_Response::success("successfully deleted category {$this->slug}");
        }
        return PWP_Response::failure("deletion of category {$this->slug} failed for unknown reasons.");
    }


    public function undo_action(): PWP_I_Response
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    private function delete_term(): bool
    {
        $term = $this->service->get_item_by_slug($this->slug);

        $this->service->unparent_children($term);

        return $this->service->delete_item($term);
    }
}
