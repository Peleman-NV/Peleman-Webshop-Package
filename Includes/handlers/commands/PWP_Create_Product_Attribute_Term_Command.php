<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response;

class PWP_Create_Product_Attribute_term_Command implements PWP_I_Command
{
    private string $name;
    private string $slug;
    private string $taxonomy;
    private string $description;
    private int $menuOrder;

    public function __construct(string $name, string $slug, string $taxonomy, string $description, int $menuOrder)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->taxonomy = $taxonomy;
        $this->description = $description;
        $this->menuOrder = $menuOrder;
    }

    public function do_action(): PWP_I_Response
    {
        if (term_exists($this->slug, $this->taxonomy)) {
            return new PWP_Error_Notice(
                'Term already exists',
                "an attribute term with name {$this->name} already exists within this taxonomy."
            );
        }
        $name_data = wp_insert_term($this->name, $this->taxonomy, array(
            'description' => $this->description,
            'slug' => $this->slug,
            'menu_order' => $this->menuOrder,
        ));
        if ($name_data instanceof \WP_Error) {
            return new PWP_Error_Notice(
                'Term creation failed',
                "Creation of product attribute {$this->name} failed",
                $name_data->error_data
            );
        }

        return new PWP_Success_Notice(
            'Term created',
            "product attribute term with name {$this->name} created successfully"
        );
    }
}
