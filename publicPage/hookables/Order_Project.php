<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;
use PWP\includes\services\entities\Project;

class Order_Project extends Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_checkout_create_order_line_item', 'order_pdf_projects', 10, 4);
    }

    public function order_pdf_projects(\WC_Order_Item_Product $item, string $cartItemKey, array $values, \WC_Order $order): void
    {
        $key = '_pdf_data';

        if (!isset($values[$key])) return;
        $meta = $values[$key];

        $project = Project::get_by_id($meta['id']);
        $project->set_ordered();
        $project->persist();

        $item->add_meta_data($key, $meta);
    }
}