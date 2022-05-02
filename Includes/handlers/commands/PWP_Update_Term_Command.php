<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\wrappers\PWP_Term_Data;

class PWP_Update_Term_Command implements PWP_I_Command
{
    private PWP_Term_SVC $service;
    private string $slug;
    private PWP_Term_Data $updateData;

    public function __construct(PWP_Term_SVC $service, string $slug, PWP_Term_Data $updateData)
    {
        $this->service = $service;
        $this->slug = $slug;
        $this->updateData = $updateData;
    }

    final public function do_action(): PWP_Response
    {
        try {
            $term = $this->update_item_by_slug();
            return new PWP_Response(
                "{$this->service->get_beauty_name()} with slug {$this->slug} has been successfully updated",
                (array)$term->data
            );
        } catch (PWP_API_Exception $exception) {
            return new PWP_Error_Response("error when updating category {$this->slug} ", $exception);
        }
    }

    final public function undo_action(): PWP_Response
    {
        return new PWP_Response("not implemented");
    }

    protected function update_item_by_slug(): \WP_TERM
    {
        $originalTerm = $this->service->get_item_by_slug($this->slug);

        if (!empty($originalTerm->parent)) {
            $this->updateData->set_parent($originalTerm->parent);
        } else {
            $parent = $this->service->get_item_by_slug($this->updateData->get_parent_slug())->term_id;
            $this->updateData->set_parent($parent ?: 0);
        }

        return $this->service->update_item($originalTerm, $this->updateData->to_array());
    }
}
