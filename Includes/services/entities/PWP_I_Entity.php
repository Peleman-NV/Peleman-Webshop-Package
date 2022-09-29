<?php

declare(strict_types=1);

namespace PWP\includes\services\entities;

interface PWP_I_Entity
{
    /**
     * Find object in database by ID/Primary Key
     *
     * @param integer $id
     * @return object|null
     */
    public static function get_by_id(int $id): ?object;
    /**
     * Save object in database if new, or update if not
     *
     * @return integer
     */
    public function persist(): void;
}
