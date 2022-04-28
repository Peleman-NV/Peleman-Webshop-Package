<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_BATCH_Endpoint;
use PWP\includes\exceptions\PWP_API_Exception;

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
            if (count($request->get_json_params()) > self::BATCH_ITEM_CAP) {
                return new \WP_REST_Response("batch request too large! maximum amount of permitted entries is " . self::BATCH_ITEM_CAP, 500);
            }
            $notices = array();

            $handler = new PWP_Category_Handler();
            foreach ($request->get_json_params()['create'] as $key => $create) {

                if (!empty($create))
                    $notices[] = $this->try_create_category($handler, $create);
            }

            foreach ($request->get_json_params()['update'] as $key => $update) {

                if (!empty($update))
                    $notices[] = $this->try_update_category($handler, $update);
            }

            foreach ($request->get_json_params()['delete'] as $key => $delete) {

                if (!empty($delete))
                    $notices[] = $this->try_delete_category($handler, $delete);
            }

            $response = array(
                'message' => __('batch successfully processed'),
                'batch notices' => $notices,
            );
        } catch (\Exception $exception) {
            $response = array(
                'message' => __('an unexpected error occured during batch processing'),
                'error logs' => array(
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ),
                'batch notices' => $notices,
            );
        } finally {
            return new \WP_REST_Response($response);
        }
    }

    final public function get_arguments(): array
    {
        return [];
    }

    private function try_update_category(PWP_Category_Handler $handler, array $data = []): array
    {
        try {
            if ($handler->does_slug_exist($data['slug'])) {
                $term = $handler->update_item_by_slug($data['slug'], $data);
                return array(
                    "message" => "successfully updated category {$term->slug}",
                    "data" => $term->data,
                );
            } else {
                return $this->try_create_category($handler, $data);
            }
        } catch (PWP_API_Exception $exception) {
            return array(
                "message" => "error when updating category {$data['slug']}",
                "error" =>  $exception->getMessage(),
            );
        } catch (\Exception $exception) {
            throw new \Exception("something went wrong trying to update an existing category.", 400, $exception);
        }
    }

    private function try_create_category(PWP_Category_Handler $handler, array $data = []): array
    {
        try {
            $term = $handler->create_item($data, $data);

            return array(
                "message" => "successfully created category {$term->slug}",
                "data" => $term->data,
            );
        } catch (PWP_API_Exception $exception) {
            return array(
                "message" => "error when creating category {$data['slug']} ",
                "error" =>  $exception->getMessage()
            );
        } catch (\Exception $exception) {
            throw new \Exception("something went wrong trying to create a new category.", 400, $exception);
        }
    }

    private function try_delete_category(PWP_Category_Handler $handler, array $data = []): array
    {
        try {
            if ($handler->delete_item_by_slug($data['slug'])) {
                return array(
                    "message" => "successfully deleted category {$data['slug']}"
                );
            }
            return array("message" => "deletion of category {$data['slug']} failed for unknown reasons.");
        } catch (PWP_API_Exception $exception) {
            return array(
                "message" => "error when deleting category {$data['slug']}:",
                "error" =>  $exception->getMessage()
            );
        } catch (\Exception $exception) {
            throw new \Exception("something went wrong trying to delete a new category.", 400, $exception);
        }
    }
}
