<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Create_Product_Attribute_term_Command implements PWP_I_Command
{
    private string $name;
    private string $slug;
    private string $taxonomy;
    private string $description;
    private int $menuOrder;

    public function __construct(string $taxonomy, string $name, string $slug,  string $description, int $menuOrder)
    {
        $this->taxonomy = $taxonomy;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->menuOrder = $menuOrder;
    }

    public function do_action(): PWP_Response
    {
        if (term_exists($this->slug, $this->taxonomy)) {
            return PWP_Response::failure(
                'failure',
                "an attribute term with name {$this->name} already exists within this taxonomy.",
                409
            );
        }
        $name_data = wp_insert_term($this->name, $this->taxonomy, array(
            'description' => $this->description,
            'slug' => $this->slug,
            'menu_order' => $this->menuOrder,
        ));
        if ($name_data instanceof \WP_Error) {
            return PWP_Response::failure(
                'failure',
                "Creation of product attribute {$this->name} failed",
                400,
                $name_data->error_data
            );
        }

        return PWP_Response::success(
            'success',
            "product attribute term with name {$this->name} created successfully",
            200,
        );
    }
}