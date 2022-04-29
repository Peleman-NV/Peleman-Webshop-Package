<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_BATCH_Endpoint;
use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\PWP_Term_Handler;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Categories_BATCH_Endpoint extends PWP_Abstract_BATCH_Endpoint
{

    private const BATCH_ITEM_CAP = 100;

    public function __construct(string $path, PWP_Authenticator $authenticator)
    {
        parent::__construct(
            $path,
            'category',
            $this->authenticator = $authenticator
        );
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $createOperations = $request->get_json_params()['create'];
            $updateOperations = $request->get_json_params()['update'];
            $deleteOperations = $request->get_json_params()['delete'];

            $updateCanCreate = (bool)$request->get_json_params()['updateCanCreate'] ?: false;

            $operations = count(array_merge($createOperations, $updateOperations, $deleteOperations));
            if ($operations > self::BATCH_ITEM_CAP) {
                return new \WP_REST_Response("batch request too large! maximum amount of permitted entries is " . self::BATCH_ITEM_CAP, 500);
            }

            $response = new PWP_Response(
                'batch',
                array(
                    'create operations' => count($createOperations),
                    'update operations' => count($updateOperations),
                    'delete operations' => count($deleteOperations),
                )
            );

            $handler = new PWP_Category_Handler();

            $this->run_operations($createOperations, $updateOperations, $deleteOperations, $handler, $updateCanCreate, $response);
        } catch (\Exception $exception) {
            $response->add_response(new PWP_Error_Response(__('an unexpected error occured during batch processing'), $exception));
        } finally {
            return new \WP_REST_Response($response->to_array());
        }
    }

    final public function get_arguments(): array
    {
        return [];
    }

    private function run_operations(array $createOps, array $updateOps, array $deleteOps, PWP_Term_Handler $handler, bool $updateCanCreate, PWP_Response $response): void
    {
        foreach ($createOps as $create) {
            $response->add_response($this->try_create_category($handler, $create));
        }

        foreach ($updateOps as $update) {
            if ($updateCanCreate) {
                $response->add_response($this->try_update_or_create_category($handler, $update));
                continue;
            }
            $response->add_response($this->try_update_category($handler, $update));
        }

        foreach ($deleteOps as $delete) {
            $response->add_response($this->try_delete_category($handler, $delete));
        }
    }

    private function try_update_or_create_category(PWP_Category_Handler $handler, array $data = []): PWP_I_Response
    {
        if (!$handler->does_slug_exist($data['slug'])) {
            return $this->try_create_category($handler, $data);
        }
        return $this->try_update_category($handler, $data,);
    }

    private function try_update_category(PWP_Category_Handler $handler, array $data = []): PWP_I_Response
    {
        try {
            $term = $handler->update_item_by_slug($data['slug'], $data);
            return new PWP_Response("successfully updated category {$term->slug}", (array)$term->data);
        } catch (PWP_API_Exception $exception) {
            return new PWP_Error_Response("error when updating category {$data['slug']} ", $exception);
        } catch (\Exception $exception) {
            throw new \Exception("something went wrong trying to update a category.", 400, $exception);
        }
    }

    private function try_create_category(PWP_Category_Handler $handler, array $data = []): PWP_I_Response
    {

        try {
            $term = $handler->create_item($data, $data);
            return new PWP_Response("successfully created category {$term->slug}", (array)$term->data);
        } catch (PWP_API_Exception $exception) {
            return new PWP_Error_Response("error when creating category {$data['slug']} ", $exception);
        } catch (\Exception $exception) {
            throw new \Exception("something went wrong trying to create a new category.", 400, $exception);
        }
    }

    private function try_delete_category(PWP_Category_Handler $handler, array $data = []): PWP_I_Response
    {
        try {
            if ($handler->delete_item_by_slug($data['slug'])) {
                return new PWP_Response("successfully deleted category {$data['slug']}");
            }
            return new PWP_Response("deletion of category {$data['slug']} failed for unknown reasons.");
        } catch (PWP_API_Exception $exception) {
            return new PWP_Error_Response("error when deleting category {$data['slug']}", $exception);
        } catch (\Exception $exception) {
            throw new \Exception("something went wrong trying to delete a category.", 400, $exception);
        }
    }
}
