<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

use PWP\includes\utilities\schemas\PWP_IProperty;

abstract class PWP_Json_Schema_Property implements PWP_IProperty
{
    protected string $type;
    private $default;
    private array $context;
    private string $description;
    private array $customArgs;
    private bool $required;
    private bool $readonly;


    public function __construct(
        string $domain,
        string $description,
        string $type,
        array $args = []
    ) {
        $this->domain = $domain;
        $this->description = esc_html__($description, $domain);
        $this->type = $type;
        $this->customArgs = $args;

        $this->context = [];
        $this->required = false;
        $this->readonly = false;
    }

    public function required($required = true): PWP_Json_Schema_Property
    {
        $this->isRequired = $required;
        return $this;
    }

    public function is_required(): bool
    {
        return $this->required ?? false;
    }

    public function readonly($readonly = true): PWP_Json_Schema_Property
    {
        $this->readonly = $readonly;
        return $this;
    }

    public function view(): PWP_Json_Schema_Property
    {
        $this->context[] = 'view';
        return $this;
    }

    public function edit(): PWP_Json_Schema_Property
    {
        $this->context[] = 'edit';
        return $this;
    }

    public function santize_callback(string $callback): PWP_IProperty
    {
        $this->customArgs['sanitize_callback'] = $callback;
        return $this;
    }

    public function validate_callback(string $callback): PWP_IProperty
    {
        $this->customArgs['validate_callback'] = $callback;
        return $this;
    }

    public function add_custom_arg(string $name, $arg): PWP_IProperty
    {
        $this->customArgs[$name] = $arg;
        return $this;
    }

    public function to_array(): array
    {
        $schema = array(
            'description' => $this->description,
            'type' => $this->type,
        );

        if ($this->readonly) {
            $schema['readonly'] = $this->readonly;
        }
        if ($this->default) {
            $schema['default'] = $this->default;
        }
        if (!empty($this->customArgs)) {
            $schema = array_merge($schema, $this->customArgs);
        }

        return $schema;
    }
}
