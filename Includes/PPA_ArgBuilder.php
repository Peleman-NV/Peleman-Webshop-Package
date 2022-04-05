<?php

declare(strict_types=1);

namespace PPA\includes;

use WP_REST_Request;

class PPA_ArgBuilder
{

    private array $args;
    private WP_REST_Request $request;

    public function __construct(array $array = [])
    {
        $args = $array;
    }

    public function set_arg(string $parameter, mixed $value): PPA_ArgBuilder
    {
        $this->args[$parameter] = $value;
        return $this;
    }

    public function add_array(array $array, bool $overrideExisting = true): PPA_ArgBuilder
    {
        if ($overrideExisting) {
            $this->args = array_merge($this->args, $array);
            return $this;
        }

        $this->args += $array;
        return $this;
    }

    public function add_arg_if_exists(WP_REST_Request $request, string $parameter): PPA_ArgBuilder
    {
        if (!empty($request[$parameter])) {
            $this->args[$parameter] = $request[$parameter];
        }
        return $this;
    }

    public function to_array(): array
    {
        return $this->args;
    }
}
