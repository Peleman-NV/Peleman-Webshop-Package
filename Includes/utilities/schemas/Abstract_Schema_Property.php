<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

use PWP\includes\utilities\schemas\I_Property;

abstract class Abstract_Schema_Property implements I_Property
{
    protected string $type;
    private $default;
    private array $context;
    private string $description;
    private array $customArgs;
    private bool $required;
    private bool $readOnly;

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
        $this->readOnly = false;
    }

    final public function required($required = true): Abstract_Schema_Property
    {
        $this->required= $required;
        return $this;
    }

    final public function is_required(): bool
    {
        return $this->required;
    }

    final public function readOnly($readOnly = true): Abstract_Schema_Property
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    final public function view(): Abstract_Schema_Property
    {
        $this->context[] = 'view';
        return $this;
    }

    final public function edit(): Abstract_Schema_Property
    {
        $this->context[] = 'edit';
        return $this;
    }

    final public function santize_callback(string $callback): I_Property
    {
        $this->customArgs['sanitize_callback'] = $callback;
        return $this;
    }

    final public function validate_callback(string $callback): I_Property
    {
        $this->customArgs['validate_callback'] = $callback;
        return $this;
    }

    final public function add_custom_arg(string $name, $arg): I_Property
    {
        $this->customArgs[$name] = $arg;
        return $this;
    }

    public function to_array(): array
    {
        $schema = array(
            'description' => $this->description,
            'type' => $this->type,
            'required' => $this->is_required(),
        );

        if ($this->readOnly) {
            $schema['readOnly'] = $this->readOnly;
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
