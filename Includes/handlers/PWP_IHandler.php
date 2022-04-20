<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

interface PWP_IHandler
{
    public function get_item(int $id, array $args = []): ?object;
    public function get_items(array $args = []): array;

    public function create_item(array $args = []): object;
    public function update_item(int $id, array $args = []): object;

    public function delete_item(int $id, array $args = []): bool;
}
