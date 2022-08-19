<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PWP_Editor_Data;
use PWP\includes\editor\PWP_IMAXEL_Data;
use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_hookable;
use WP_Post;

class PWP_Save_Parent_Custom_Fields extends PWP_Abstract_Action_hookable
{
    public function __construct()
    {
        parent::__construct(
            'woocommerce_process_product_meta',
            'save_variables',
            11,
            2
        );
    }

    public function save_variables(int $postId, WP_Post $post): void
    {
    }
}
