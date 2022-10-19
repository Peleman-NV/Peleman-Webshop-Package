<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\includes\services\entities\PWP_Project;

class PWP_Delete_Projects_On_Order_Cancelled extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_order_status_cancelled', 'delete_projects');
    }

    public function delete_projects(int $order_id): void
    {
        $order = wc_get_order($order_id);
        if (!$order) return;

        $items = $order->get_items();

        foreach ($items as $i => $item) {
            if (!$item->get_meta('_pdf_data')) return;
            $pdf_data = $item->get_meta('_pdf_data');
            $id = (int)$pdf_data['id'];

            $project = PWP_Project::get_by_id($id);
            $project->delete_files();
            $project->delete();
        }
    }
}