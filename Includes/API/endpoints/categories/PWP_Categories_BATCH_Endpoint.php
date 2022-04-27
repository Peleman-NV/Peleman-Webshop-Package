<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\API\endpoints\PWP_Abstract_BATCH_Endpoint;

class PWP_Categories_BATCH_Endpoint extends PWP_Abstract_BATCH_Endpoint
{

    public function __construct(string $path, PWP_Authenticator $authenticator)
    {
        parent::__construct($path, 'product categories', $authenticator);
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        $updates = $request['update'];
        $creates = $request['create'];
        $deletes = $request['delete'];

        $notices = array();

        $handler = new PWP_Category_Handler();

        // foreach ($updates as $update) {
        //     try {
        //         $term = $handler->update_item_by_slug($update['slug'], $update);
        //         $notices[] = "successfully updated category {$term->slug}";
        //     } catch (\Exception $exception) {
        //         $notices[] = "error when updating category {$update['id']}: {$exception->getMessage()}";
        //     }
        // }

        // foreach ($creates as $create) {
        //     try {
        //         $term = $handler->create_item($create['name'], $create);
        //         $notices[] = "successfully created category {$term->name}";
        //     } catch (\Exception $exception) {
        //         $notices[] = "error when creating category {$create['name']}: {$exception->getMessage()}";
        //     }
        // }

        // foreach ($deletes as $delete) {
        //     $notices[] = $handler->delete_item_by_slug($delete['slug'], $create);
        // }

        return new \WP_REST_Response("we're not quite there yet, but we will be soon!", 501);
    }

    final public function authenticate(\WP_REST_Request $request): bool
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    final public function get_arguments(): array
    {
        return [];
    }
}
