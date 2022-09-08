<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response_Component;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Create_Product_Attribute_Command implements PWP_Create_Term_Command
{
    private string $name;
    private string $slug;
    private string $type;
    private string $orderBy;
    private bool $hasArchives;

    private string $taxonomy;

    public function __construct(string $name, string $slug, string $type, string $orderBy, bool $hasArchives)
    {
        $this->name = $name;
        $this->slug = stripslashes($slug);
        $this->type = $type;
        $this->orderBy = $orderBy;
        $this->hasArchives = $hasArchives;

        $this->taxonomy = 'pa_' . $this->slug;
    }

    public function do_action(): PWP_I_Response_Component
    {

        if (taxonomy_exists($this->taxonomy)) {
            return new PWP_Error_Notice(
                'attribute creation failed',
                'cannot create attribute, as the taxonomy already exists',
            );
        }

        $id = wc_create_attribute(array(
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'order_by' => $this->orderBy,
            'has_archives' => $this->hasArchives,
        ));

        if ($id instanceof \WP_Error) {
            return new PWP_Error_Notice(
                'attribute creation failed',
                'something went wrong when trying to create the attribute'
            );
        };
    }

    public function get_taxonomy(): string
    {
        return $this->taxonomy;
    }
}
