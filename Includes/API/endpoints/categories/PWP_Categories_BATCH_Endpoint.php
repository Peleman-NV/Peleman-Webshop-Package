<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\commands\PWP_I_Command;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;
use PWP\includes\API\endpoints\PWP_Abstract_BATCH_Endpoint;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;

class PWP_Categories_BATCH_Endpoint extends PWP_Abstract_BATCH_Endpoint
{
    private const BATCH_ITEM_CAP = 200;

    /**
     * @var PWP_I_Command[]
     */
    private array $commands;

    public function __construct(PWP_Channel_Definition $channel, PWP_I_Api_Authenticator $authenticator)
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

    final public function get_arguments(): array
    {
        // $schema = new PWP_Argument_Schema(new PWP_Schema_Factory(), $this->title);
        // $schema->add_bool_property(
        //     "update_can_create",
        //     "whether the update batch is capable of creating new entries, in case the original cannot be found."
        // )->default(false);
        // $schema->add_bool_property(
        //     "can_change_parent",
        //     "whether an update call can change the parent of a category. if false, can only give a parent to a category that did not have one before."
        // )->default(false);
        // $schema->add_array_property(
        //     "create",
        //     "array of create calls."
        // );
        // $schema->add_array_property(
        //     "update",
        //     "array of update calls. if add_bool_property is true, can also include create calls."
        // );
        // $schema->add_array_property(
        //     "delete",
        //     "array of delete calls."
        // );

        // return $schema->to_array();
        return array();
    }

    private function handle_request(array $request): PWP_Response
    {
        $createOperations = (array)$request['create'];
        $updateOperations = (array)$request['update'];
        $deleteOperations = (array)$request['delete'];


        $updateCanCreate = (bool)$request['update_can_create'] ?: false;
        $canChangeParent = (bool)$request['can_change_parent'] ?: false;

        $operations = count(array_merge($createOperations, $updateOperations, $deleteOperations));
        if ($operations > self::BATCH_ITEM_CAP) {
            return new \WP_REST_Response("batch request too large! maximum amount of permitted entries is " . self::BATCH_ITEM_CAP, 500);
        }

        $response = new PWP_Response(
            'batch',
            true,
            array(
                'create operations' => count($createOperations),
                'update operations' => count($updateOperations),
                'delete operations' => count($deleteOperations),
            )
        );

        $this->generate_commands($createOperations, $updateOperations, $deleteOperations, $updateCanCreate, $canChangeParent);
        $this->execute_commands($response);

        $response->add_response(PWP_Response::success("operation completed!"));
        return $response;
    }

    private function generate_commands(array $createOps, array $updateOps, array $deleteOps, bool $updateCanCreate = false, bool $canChangeParent = false): void
    {
        $factory = new PWP_Category_Command_Factory();

        foreach ($createOps as $create) {
            $data = new PWP_Term_Data($create);
            $this->commands[] = $factory->new_create_term_command($data);
        }

        foreach ($updateOps as $update) {
            $data = new PWP_Term_Data($update);
            if ($updateCanCreate) {
                $this->commands[] = $factory->new_create_or_update_command($data, $canChangeParent);
                continue;
            }
            $this->commands[] = $factory->new_update_term_command($data, $canChangeParent);
        }

        foreach ($deleteOps as $delete) {
            $data = new PWP_Term_Data($delete);
            $slug = $data->get_slug();
            if (!is_null($slug)) {
                $this->commands[] = $factory->new_delete_term_command($slug);
            }
        }
    }

    private function execute_commands(PWP_Response $response): void
    {
        foreach ($this->commands as $command) {
            $notification = $command->do_action();
            $response->add_response($notification);
        }
    }
}
