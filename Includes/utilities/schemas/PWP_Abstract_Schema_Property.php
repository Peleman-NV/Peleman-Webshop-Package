<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

use PWP\includes\utilities\schemas\PWP_I_Property;

abstract class PWP_Abstract_Schema_Property implements PWP_I_Property
{
    protected string $type;
    private $default;
    private array $context;
    private string $description;
    private array $customArgs;
    private bool $required;
    private bool $readonly;

    public function __construct(
        string $description,
        string $type,
        array $args = []
    ) {
        $this->description = $description;
        $this->type = $type;
        $this->customArgs = $args;

        $this->context = [];
        $this->required = false;
        $this->readonly = false;
    } 

    final public function required($required = true): PWP_Abstract_Schema_Property
    {
        $this->isRequired = $required;
        return $this;
    }

    final public function is_required(): bool
    {
        return $this->required ?? false;
    }

    final public function readonly($readonly = true): PWP_Abstract_Schema_Property
    {
        $this->readonly = $readonly;
        return $this;
    }

    final public function view(): PWP_Abstract_Schema_Property
    {
        $this->context[] = 'view';
        return $this;
    }

    final public function edit(): PWP_Abstract_Schema_Property
    {
        $this->context[] = 'edit';
        return $this;
    }

    final public function santize_callback(string $callback): PWP_I_Property
    {
        $this->customArgs['sanitize_callback'] = $callback;
        return $this;
    }

    final public function validate_callback(string $callback): PWP_I_Property
    {
        $this->customArgs['validate_callback'] = $callback;
        return $this;
    }

    final public function add_custom_arg(string $name, $arg): PWP_I_Property
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
