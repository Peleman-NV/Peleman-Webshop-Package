<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use Exception;
use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_BATCH_Endpoint;
use PWP\includes\exceptions\PWP_API_Exception;
use WP_REST_Response;

class PWP_Categories_BATCH_Endpoint extends PWP_Abstract_BATCH_Endpoint
{

    private const BATCH_ITEM_CAP = 100;

    public function __construct(string $path, PWP_Authenticator $authenticator)
    {
        parent::__construct($path, 'product categories', $authenticator);
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            if (count($request->get_body_params()) > self::BATCH_ITEM_CAP) {
                return new \WP_REST_Response("batch request too large! maximum amount of permitted entries is " . self::BATCH_ITEM_CAP, 500);
            }

            $creates = $request->get_body_params()['create'];
            $updates = $request->get_body_params()['update'];
            $deletes = $request->get_body_params()['delete'];

            $notices = array();

            $handler = new PWP_Category_Handler();
            foreach ($creates as $create) {
                $notices[$create['slug']] = $this->try_create_category($handler, $create);
            }

            foreach ($updates as $update) {
                $notices[$update['slug']] = $this->try_update_category($handler, $update);
            }

            foreach ($deletes as $delete) {
                $notices[$delete['slug']] = $this->try_delete_category($handler, $delete);
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

    private function try_update_category(PWP_Category_Handler $handler, array $data = []): string
    {
        try {
            $term = $handler->update_item_by_slug($data['slug'], $data);
            return "successfully updated category {$term->slug}";
        } catch (PWP_API_Exception $exception) {
            return "error when updating category {$data['slug']}: " . $exception->getMessage();
        }
    }

    private function try_create_category(PWP_Category_Handler $handler, array $data = []): string
    {
        try {
            $term = $handler->create_item($data['name'], $data);
            return "successfully created category {$term->name}";
        } catch (PWP_API_Exception $exception) {
            return "error when creating category {$data['name']}: " . $exception->getMessage();
        }
    }

    private function try_delete_category(PWP_Category_Handler $handler, array $data = []): string
    {
        try {
            if ($handler->delete_item_by_slug($data['slug'])) {
                return "successfully deleted category {$data['slug']}";
            }
            return "deletion of category {$data['slug']} failed for unknown reasons.";
        } catch (PWP_API_Exception $exception) {

            return "error when deleting category {$data['slug']}:" . $exception->getMessage();
        }
    }
}
