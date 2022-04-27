<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\wrappers\PWP_Component;
use PWP\includes\wrappers\PWP_I_Component;

interface PWP_I_Handler
{
    public function create_item(array $createData, array $args = []): object;

    public function get_item(int $id, array $args = []): ?object;
    public function get_items(array $args = []): array;

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param array $args
     * @param boolean $useEmpty default false. determines if values that have been left empty in the args
     * should be persisted anyway.
     * @return object
     */
    public function update_item(int $id, array $updateData, array $args = [], bool $useNullValues = false): object;

    public function delete_item(int $id, array $args = []): bool;
}
