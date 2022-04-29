<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\utilities\PWP_ILogger;

/**
 * Undocumented class
 * 
 * @deprecated version
 */
class PWP_Product_Attribute_Handler implements PWP_I_Handler
{

    public function __construct(PWP_ILogger $logger)
    {
        $this->logger = $logger;
    }

    public function get_item(int $id, array $args = []): object
    {
        return wc_get_attribute($id);
    }

    public function get_items(array $args = []): array
    {
        $ids = wc_get_attribute_taxonomy_ids();
        $attributes = array();
        foreach ($ids as $id) {
            $attributes[] = wc_get_attribute($id);
        }

        return $attributes;
    }

    public function create_item(array $createData, array $args = []): object
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    public function update_item(int $id, array $updateData, array $args = [], bool $useNullValues = false): object
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    public function delete_item(int $id, array $args = []): bool
    {
        return false;
    }

    public function get_attribute_by_slug(string $slug): object
    {
        $attributes = $this->get_items();
        $attributes = array_filter($attributes, function ($e) use ($slug) {
            return $e['slug'] === $slug;
        });

        return $attributes[0];
    }
}
