<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use wpdb;

class PWP_WPDB
{
    public wpdb $db;
    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }

    public function prefix(): string
    {
        return $this->db->prefix;
    }

    public function prepare(string $query, ...$args): ?string
    {
        return $this->db->prepare($query, $args);
    }

    public static function get_wpdb(): wpdb
    {
        global $wpdb;
        return $wpdb;
    }
}
