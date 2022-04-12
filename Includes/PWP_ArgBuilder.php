<?php

declare(strict_types=1);

namespace PWP\includes;

use WP_REST_Request;

class PWP_ArgBuilder
{

    private array $args;

    public function __construct(array $array = array())
    {
        $this->args = $array;
    }

    public function set_arg(string $parameter, mixed $value): PWP_ArgBuilder
    {
        $this->args[$parameter] = $value;
        return $this;
    }

    public function add_array(array $array, bool $overrideExisting = true): PWP_ArgBuilder
    {
        if ($overrideExisting) {
            $this->args = array_merge($this->args, $array);
            return $this;
        }

        $this->args += $array;
        return $this;
    }

    public function add_arg(string $parameter, $argument): PWP_ArgBuilder
    {
        $this->args[$parameter] = $argument;
        return $this;
    }
    public function add_arg_if_exists(WP_REST_Request $request, string $parameter): PWP_ArgBuilder
    {
        if (!empty($request[$parameter])) {
            $this->args[$parameter] = $request[$parameter];
        }
        return $this;
    }

    public function add_arg_if_not_null(string $parameter, $argument): PWP_ArgBuilder
    {
        if (!is_null($argument)) {
            $this->args[$parameter] = $argument;
        }
        return $this;
    }

    public function add_arg_if_not_empty(string $parameter, $argument): PWP_ArgBuilder
    {
        if (!empty($argument)) {
            $this->args[$parameter] = $argument;
        }
        return $this;
    }

    public function to_array(): array
    {
        return $this->args;
    }
}
