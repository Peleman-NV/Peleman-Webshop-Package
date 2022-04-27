<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\API\PWP_API_Logger;
use PWP\includes\utilities\PWP_Null_Logger;
use PWP\includes\handlers\PWP_Product_Handler;
use PWP\includes\utilities\schemas\PWP_ISchema;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use WP_REST_Server;

class PWP_Products_Endpoint extends PWP_EndpointController implements PWP_I_Endpoint
{
    private const PAGE_SOFT_CAP = 100;

    public function __construct(PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            "/products",
            'product',
            $this->authenticator = $authenticator

        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        return new WP_REST_Response('not implemented', 503);
    }

    public function authenticate(WP_REST_Request $request): bool
    {
        return true;
    }

    public function get_methods(): string
    {
        return WP_REST_Server::ALLMETHODS;
    }

    public function create_item(WP_REST_Request $request): WP_REST_Response
    {
        $logger = (bool)$request['logs'] ? new PWP_API_Logger() :  new PWP_Null_Logger();
        $handler = new PWP_Product_Handler($logger);

        try {
            $result = $handler->create_item($request['name'], $request->get_body_params());
        } catch (\Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), $exception->getCode());
        }

        return new WP_REST_Response(array(
            'good job! here are your args:',
            $request->get_body_params(),
            $result->get_data(),
        ));
    }

    public function get_item(WP_REST_Request $request): WP_REST_Response
    {
        $logger = (bool)$request['logs'] ? new PWP_API_Logger() :  new PWP_Null_Logger();
        $handler = new PWP_Product_Handler($logger);
        $product = $handler->get_item($request['id'], $request->get_url_params());

        return new \WP_REST_Response($product->get_data());
    }

    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        $logger = (bool)$request['logs'] ? new PWP_API_Logger() :  new PWP_Null_Logger();
        $handler = new PWP_Product_Handler($logger);
        $args = $request->get_url_params();

        $results = $handler->get_items($args);
        $results['logs'] = $logger->get_logs();

        return new \WP_REST_Response($results);
    }

    public function update_item(WP_REST_Request $request): WP_REST_Response
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    public function delete_item(WP_REST_Request $request): WP_REST_Response
    {
        $logger = (bool)$request['logs'] ? new PWP_API_Logger() :  new PWP_Null_Logger();
        $handler = new PWP_Product_Handler($logger);
        $handler->delete_item($request['id'], $request->get_body_params());

        return new WP_REST_Response(array(
            'message' => isset($request->get_body_params()['force']) ?
                'product permanently deleted successfullly!' :
                'product moved to trash successfully!',
        ));
    }

    public function get_arguments(): array
    {
        $factory = new PWP_Schema_Factory(PWP_TEXT_DOMAIN);
        $schema = new PWP_Argument_Schema();
        $schema
            ->add_property(
                'SKU',
                $factory->string_property('filter results to matching SKUs. supports partial matches.')
                    ->view()
            )->add_property(
                'limit',
                $factory->int_property('maximum amount of results per call')
                    ->default(self::PAGE_SOFT_CAP)
            )->add_property(
                'page',
                $factory->int_property('when using pageination, represents the page of results to retrieve.')
                    ->default(1)
                    ->add_custom_arg('min', -1)
                    ->add_custom_arg('sanitize_callback', 'absint')
            )->add_property(
                'order',
                $factory->enum_property('how to order; ascending or descending', array(
                    'ASC',
                    'DESC'
                ))->default('ASC')
            )->add_property(
                'type',
                $factory->enum_property('types to match', array_keys(wc_get_product_types()))
            )->add_property(
                'orderby',
                $factory->enum_property('by which parameter to order the resulting output', array(
                    'none',
                    'id',
                    'name',
                    'type',
                    'rand',
                    'date',
                    'modified',
                ))->default('id')
            )->add_property(
                'tag',
                $factory->array_property('limit results to specific tags by slug')
            )->add_property(
                'category',
                $factory->array_property('limit results to specific categories by slug')
            )->add_property(
                'status',
                $factory->multi_enum_property('status to match', array(
                    'draft',
                    'pending',
                    'private',
                    'published',
                    'trash',
                ))
            )->add_property(
                'price',
                $factory->string_property('exact price to match')
            )->add_property(
                'logs',
                $factory->bool_property('whether the function will return a log of events or not, included in the response.')
            );

        return $schema->to_array();
    }
}
