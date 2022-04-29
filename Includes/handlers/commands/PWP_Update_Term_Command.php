<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\PWP_Term_Handler;
use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\wrappers\PWP_Term_Data;

final class PWP_Update_Term_Command implements PWP_I_Command
{
    private PWP_Term_Handler $handler;
    private string $slug;
    private PWP_Term_Data $updateData;

    public function __construct(PWP_Term_Handler $handler, string $slug, PWP_Term_Data $updateData)
    {
        $this->handler = $handler;
        $this->slug = $slug;
        $this->updateData = $updateData;
    }

    public function do_action(): PWP_I_Response
    {
        try {
            $term = $this->handler->update_item_by_slug($this->slug, $this->updateData->to_array(), [], false);
            return new PWP_Response(
                "{$this->handler->get_service()->get_beauty_name()} with slug {$this->slug} has been successfully updated",
                (array)$term->data
            );
        } catch (PWP_API_Exception $exception) {
            return new PWP_Error_Response("error when updating category {$this->slug} ", $exception);
        } catch (\Exception $exception) {
            throw new \Exception("something went wrong trying to update a category.", 400, $exception);
        }
    }

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Response("not implemented");
    }
}
