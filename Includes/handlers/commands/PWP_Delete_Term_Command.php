<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use WP_Term;
use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\validation\PWP_Validation_Handler;
use PWP\includes\validation\PWP_Abstract_Term_Handler;
use PWP\includes\utilities\notification\PWP_Notification;
use PWP\includes\validation\PWP_Validate_Term_Slug_Exists;
use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response;

final class PWP_Delete_Term_Command implements PWP_I_Command
{
    private PWP_Term_SVC $service;
    private string $slug;

    private PWP_Abstract_Term_Handler $handler;

    public function __construct(PWP_Term_SVC $service, string $slug)
    {
        $this->service = $service;
        $this->slug = $slug;

        $this->handler = new PWP_Validation_Handler($this->service);
        $this->handler->set_next(new PWP_Validate_Term_Slug_Exists($this->service));
    }

    public function do_action(): PWP_I_Response
    {
        $notification = new PWP_Notification();
        $data = new PWP_Term_Data(['slug' => $this->slug]);
        $response = $this->handler->handle($data, $notification);

        if (!$response) return $notification;
        if ($this->delete_term()) {
            return $notification->add_error(
                "category could not be deleted",
                "deletion of {$this->service->get_taxonomy_name()} {$this->slug} failed for unknown reasons."
            );
        }

        return new PWP_Success_Notice(
            "term deleted",
            "term {$this->service->get_taxonomy_name()} with slug {$this->slug} has been successfully deleted!"
        );
    }


    public function undo_action(): PWP_I_Response
    {
        return new PWP_Error_Notice(
            "method not implemented",
            "method " . __METHOD__ . " not implemented"
        );
    }

    private function delete_term(): bool
    {
        $term = $this->service->get_item_by_slug($this->slug);

        $this->service->unparent_children($term);

        return $this->service->delete_item($term);
    }
}
