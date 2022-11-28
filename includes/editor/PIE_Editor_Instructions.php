<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use WC_Product;

/**
 * wrapper class for PIE Editor Instruction metadata on products and product variations, and handling wordpress
 * metadata serialization of arrays.
 * PIE Editor Instructions are stored as a string of instructions separated by spaces.
 */
class PIE_Editor_Instructions extends Product_Meta
{
    public const EDITOR_INSTRUCTIONS_KEY    = 'pie_editor_instructions';
    public const INSTRUCTION_PREFIX         = 'pie_instruct_';

    private array $instructions;

    public function __construct(WC_Product $parent)
    {
        $this->parent = $parent;
        $meta = $this->parent->get_meta(self::EDITOR_INSTRUCTIONS_KEY);
        if ($meta) {
            $this->instructions = explode(' ', $meta);
            return;
        }
        $this->instructions = [];
    }

    public function add_instruction(string $instruction): self
    {
        if (!in_array($instruction, $this->instructions, true)) {
            $this->instructions[] = $instruction;
        }
        return $this;
    }

    public function remove_instruction(string $instruction): self
    {

        $this->instructions = array_diff($this->instructions, array($instruction));
        return $this;
    }

    public function set_instructions(array $instructions): self
    {
        $this->instructions = $instructions;
        return $this;
    }

    public function get_instructions(): array
    {
        return $this->instructions;
    }

    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(
            self::EDITOR_INSTRUCTIONS_KEY,
            (implode(' ', $this->instructions))
        );
        $this->parent->save_meta_data();
    }
}
