<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\utilities\PWP_ILogger;

interface PWP_IHandler
{
    public function create_item(string $identifier, array $args = []): object;

    public function get_item(int $id, array $args = []): ?object;
    public function get_items(array $args = []): array;

    public function update_item(int $id, array $args = []): object;

    public function delete_item(int $id, array $args = []): bool;

    public function batch_items(array $data, array $args = []): array;
}
