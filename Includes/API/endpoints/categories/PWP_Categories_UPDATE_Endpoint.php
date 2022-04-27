<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_UPDATE_Endpoint;

class PWP_Categories_UPDATE_Endpoint extends PWP_Abstract_UPDATE_Endpoint
{
    public function __construct(string $path, PWP_Authenticator $authenticator)
    {
        parent::__construct(
            $path .  "/categories/(?P<slug>\w+)",
            'product categories',
            $authenticator
        );
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $slug = $request['slug'];
            $handler = new PWP_Category_Handler();

            $term = $handler->update_item_by_slug($slug, $request->get_body_params());
            return new \WP_REST_Response(array(
                'message' => 'category successfully updated!',
                'data' => $term->data,
            ));
        } catch (PWP_API_Exception $exception) {
            return $exception->to_rest_response();
        }
    }

    final public function authenticate(\WP_REST_Request $request): bool
    {
        return true;
    }
}
