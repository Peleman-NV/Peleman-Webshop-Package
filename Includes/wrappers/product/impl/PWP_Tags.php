<?php

declare(strict_types=1);

namespace PWP\includes\wrappers\product;

use PWP\includes\handlers\PWP_Tag_Handler;
use PWP\includes\wrappers\PWP_Component;

class PWP_Tags extends PWP_Component
{
    public function get_term_ids_from_slugs()
    {
        $handler = new PWP_Tag_Handler();
        foreach ($this->data as $term) {
            if (!is_int($term->slug)) {
                $term->id = $handler->get_item_by_slug($term->slug)->term_id;
            }
        }
    }
}
