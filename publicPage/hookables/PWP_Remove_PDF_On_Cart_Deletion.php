<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\includes\services\entities\PWP_Project;
use PWP\includes\wrappers\PWP_PDF_Data;

class PWP_Remove_PDF_On_Cart_Deletion extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_remove_cart_item', 'remove_pdf_project', 10, 2);
    }

    public function remove_pdf_project(string $cart_item_id, \WC_Cart $cart): void
    {
        $item = $cart->get_cart_item($cart_item_id);
        // error_log(print_r($item, true));

        if (!isset($item['_pdf_data'])) return;

        $data = $item['_pdf_data'];
        $id = (int)$data['id'];

        if (0 >= $id) return;

        error_log("removing project from pwp uploads: {$id}");

        $this->delete_from_database($id);
        $this->delete_directory_and_files($id);
    }

    private function delete_from_database(int $id): void
    {
        $project = PWP_Project::get_by_id($id);
        $project->delete();
    }

    private function delete_directory_and_files(int $id): void
    {
        $directory = realpath(PWP_UPLOAD_DIR) . "/{$id}/";
        array_map('unlink', array_filter(array_filter((array) glob($directory . '*') ?: [])));
        rmdir($directory);
    }
}
