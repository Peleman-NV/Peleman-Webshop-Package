<?php

declare(strict_types=1);

namespace PWP\includes\handlers\services;

interface PWP_I_SVC
{
    public function get_item_by_name(string $name): ?object;
    public function get_item_by_id(int $id): ?object;
    public function get_item_by_slug(string $slug): ?object;
}
