<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use WP_REST_Request;

class ArgBuilder
{

    private array $args;

    public function __construct(array $array = array())
    {
        $this->args = $array;
    }

    /**
     * add array of args to existing args
     *
     * @param array $array
     * @param boolean $overrideExisting default true; if false it will override existing keys. if true, will result in duplicate keys
     * @return ArgBuilder
     */
    public function add_array(array $array, bool $overrideExisting = true): ArgBuilder
    {
        if ($overrideExisting) {
            $this->args = array_merge($this->args, $array);
            return $this;
        }

        $this->args += $array;
        return $this;
    }

    public function add_arg(string $key, $value, $default = null): ArgBuilder
    {
        if (isset($value) && $value !== '') {
            $this->args[$key] = $value;
            return $this;
        }
        if (!is_null($default)) {
            $this->args[$key] = $default;
        }
        return $this;
    }

    public function add_arg_from_request(WP_REST_Request $request, string $key, $default = null): ArgBuilder
    {
        return $this->add_arg($key, $request[$key], $default);
    }

    public function add_arg_from_array(array $source, string $key, $default = null): ArgBuilder
    {
        return $this->add_arg($key, $source[$key], $default);
    }

    /**
     * attempts to add required argument. if the argument is not set, will throw an error.
     *
     * @param string $key
     * @param [type] $value
     * @return ArgBuilder
     */
    public function add_required_arg(string $key, $value): ArgBuilder
    {
        if (isset($value) && $value !== '') {
            $this->args[$key] = $value;
            return $this;
        }
        throw new \Exception("missing required argument: {$key}", 400);
    }

    /**
     * attempts to find a required argument in a request and add it to the arg array. if the argument is not set, will throw an error.
     *
     * @param WP_REST_Request $request
     * @param string $key
     * @return ArgBuilder
     */
    public function add_required_arg_from_request(WP_REST_Request $request, string $key): ArgBuilder
    {
        return $this->add_required_arg($key, $request[$key]);
    }

    /**
     * will try to add a value to the arg array. will not add the key and value pair if the value is null
     *
     * @param string $key
     * @param [type] $value
     * @return ArgBuilder
     */
    public function add_arg_if_not_null(string $key, $value): ArgBuilder
    {
        if (!is_null($value)) {
            $this->args[$key] = $value;
        }
        return $this;
    }

    /**
     * will try to add a value to the arg array. will not add the key and value pair if the value is empty
     *
     * @param string $key
     * @param [type] $value
     * @return ArgBuilder
     */
    public function add_arg_if_not_empty(string $key, $value): ArgBuilder
    {
        if (!empty($value)) {
            $this->args[$key] = $value;
        }
        return $this;
    }

    public function get_arg(string $key): ?mixed
    {
        return $this->args[$key] ?: null;
    }
    
    /**
     * returns arg array
     *
     * @return array
     */
    public function to_array(): array
    {
        return $this->args;
    }
}
