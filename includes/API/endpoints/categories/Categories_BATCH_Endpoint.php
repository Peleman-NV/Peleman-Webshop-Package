<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\wrappers\Term_Data;
use PWP\includes\handlers\commands\I_Command;
use PWP\includes\utilities\response\Response;
use PWP\includes\API\endpoints\Abstract_BATCH_Endpoint;
use PWP\includes\API\Channel_Definition;
use PWP\includes\authentication\I_Api_Authenticator;
use PWP\includes\handlers\commands\Category_Command_Factory;

class Categories_BATCH_Endpoint extends Abstract_BATCH_Endpoint
{
    private const BATCH_ITEM_CAP = 200;

    /**
     * @var I_Command[]
     */
    private array $commands;

    public function __construct(Channel_Definition $channel, I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $channel->get_namespace(),
            $channel->get_route() . "/batch",
            $channel->get_title(),
            $this->authenticator = $authenticator
        );

        $this->commands = array();
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        $schema = $this->get_arguments();
        $request = $request->get_json_params();

        rest_validate_value_from_schema($request, $schema);
        $sanitized = rest_sanitize_value_from_schema($request, $schema);

        if (!is_wp_error($sanitized)) {
            $response = $this->handle_request($sanitized);
            return new \WP_REST_Response($response->to_array());
        }
        return new \WP_REST_Response($sanitized->errors, 400);
    }

    private function handle_request(array $request): Response
    {
        $createOperations = (array)$request['create'];
        $updateOperations = (array)$request['update'];
        $deleteOperations = (array)$request['delete'];


        $updateCanCreate = (bool)$request['update_can_create'] ?: false;
        $canChangeParent = (bool)$request['can_change_parent'] ?: false;

        $operations = count(array_merge($createOperations, $updateOperations, $deleteOperations));
        if ($operations > self::BATCH_ITEM_CAP) {
            return new \WP_REST_Response(
                "batch request too large! maximum amount of permitted entries is " . self::BATCH_ITEM_CAP,
                500
            );
        }

        $response = Response::success(
            'batch',
            'product batch',
            200,
            array(
                'create operations' => count($createOperations),
                'update operations' => count($updateOperations),
                'delete operations' => count($deleteOperations),
            )
        );

        $this->generate_commands($createOperations, $updateOperations, $deleteOperations, $updateCanCreate, $canChangeParent);
        $this->execute_commands($response);

        $response->add_response(Response::success(
            "operation completed!",
            "Batch operation completed successfully."
        ));
        return $response;
    }

    private function generate_commands(array $createOps, array $updateOps, array $deleteOps, bool $updateCanCreate = false, bool $canChangeParent = false): void
    {
        $factory = new Category_Command_Factory();

        foreach ($createOps as $create) {
            $data = new Term_Data($create);
            $this->commands[] = $factory->new_create_term_command($data);
        }

        foreach ($updateOps as $update) {
            $data = new Term_Data($update);
            if ($updateCanCreate) {
                $this->commands[] = $factory->new_create_or_update_command($data, $canChangeParent);
                continue;
            }
            $this->commands[] = $factory->new_update_term_command($data, $canChangeParent);
        }

        foreach ($deleteOps as $delete) {
            $data = new Term_Data($delete);
            $slug = $data->get_slug();
            if (!is_null($slug)) {
                $this->commands[] = $factory->new_delete_term_command($slug);
            }
        }
    }

    private function execute_commands(Response $response): void
    {
        foreach ($this->commands as $command) {
            $notification = $command->do_action();
            $response->add_response($notification);
        }
    }

    public function get_schema(): array
    {
        return [];
    }
}
