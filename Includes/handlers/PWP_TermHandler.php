<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use stdClass;
use WP_Error;
use Requests_Exception_HTTP_404;

abstract class PWP_TermHandler
{
    protected string $myType;
    protected string $myTypeLong;

    public abstract function create(string $name, string $slug, string $parent = '', array $args = []): void;
    public abstract function update(stdClass $itemData): void;

    protected function add_new_term(string $name, string $taxonomy, array $args = []): array
    {

        $result = \wp_insert_term($name, $taxonomy, $args);
        if ($result instanceof WP_Error) {
            throw new Requests_Exception_HTTP_404("something went wrong when trying to create a new term");
        }

        return $result;
    }

    final protected function find_parent_by_slug(string $slug): ?\WP_Term
    {
        $result = get_term_by('slug', $slug, $this->myType, object);
        return !$result ? $result : null;
    }

    final protected function find_parent_by_name(string $name): ?\WP_Term
    {
        $result = get_term_by('name', $name, $this->myType, object);
        return !$result ? $result : null;
    }
}
