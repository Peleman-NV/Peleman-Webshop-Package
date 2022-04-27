<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\wrappers\PWP_Component;
use PWP\includes\wrappers\PWP_I_Component;

interface PWP_I_Slug_Handler
{


    public function get_item_by_slug(string $slug, array $args = []): ?object;

    public function update_item_by_slug(string $slug, array $updateData, array $args = [], bool $useNullValues = false): object;

    public function delete_item_by_slug(string $slug, array $args = []): bool;
}
