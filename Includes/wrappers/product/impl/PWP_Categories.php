<?php

declare(strict_types=1);

namespace PWP\includes\wrappers\product\impl;

use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\wrappers\PWP_Component;

class PWP_Categories extends PWP_Component
{
    public function get_term_ids_from_slugs()
    {
        $handler = new PWP_Category_Handler();
        foreach ($this->data as $term) {
            if (!is_int($term->slug)) {
                $term->id = $handler->get_item_by_slug($term->slug)->term_id;
            }
        }
    }
}
