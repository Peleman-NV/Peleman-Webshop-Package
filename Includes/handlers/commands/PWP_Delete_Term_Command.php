<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Error_Response;

final class PWP_Delete_Term_Command implements PWP_I_Command
{
    private PWP_Term_SVC $service;
    private string $slug;

    public function __construct(PWP_Term_SVC $service, string $slug)
    {
        $this->service = $service;
        $this->slug = $slug;
    }

    public function do_action(): PWP_I_Response
    {
        try {
            if ($this->delete_term()) {
                return new PWP_Response("successfully deleted category {$this->slug}");
            }
            return new PWP_Response("deletion of category {$this->slug} failed for unknown reasons.");
        } catch (PWP_API_Exception $exception) {
            return new PWP_Error_Response("error when deleting category {$this->slug}", $exception);
        }
    }

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Response("not implemented");
    }

    private function delete_term(): bool
    {
        if (!$this->service->is_slug_in_use($this->slug)) {
            throw new PWP_Not_Found_Exception("term with slug {$this->slug} not found");
        }
        $term = $this->service->get_item_by_slug($this->slug);
        return $this->service->delete_item($term);
    }
}
