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

    public const USE_DESIGN_MODE = 'usedesignmode';
    public const USE_IMAGE_UPLOAD = 'useimageupload';
    public const USE_BACKGROUNDS = 'usebackgrounds';
    public const USE_DESIGNS = 'usedesigns';
    public const USE_ELEMENTS = 'uselements';
    public const USE_DOWNLOAD_PREVIEW = 'usedownloadpreview';
    public const USE_OPEN_FILE = 'useopenfile';
    public const USE_EXPORT = 'useexport';
    public const USE_SHOW_CROPZONE = 'useshowcropzone';
    public const USE_SHOW_SAFEZONE = 'useshowsafezone';
    public const USE_STOCK_PHOTOS = 'usestockphotos';
    public const USE_TEXT = 'usetext';
    public const USE_LAYERS = 'uselayers';
    public const USE_QR = 'useqr';

    private array $instructions;

    public function __construct(WC_Product $parent)
    {
        $this->parent = $parent;
        $this->instructions = array();

        $this->add_instruction('usedesignmode', 'use design mode');
        $this->add_instruction('useImageUpload', 'use image upload', false, 'Allow user to upload images');
        $this->add_instruction('usebackgrounds', 'use backgrounds');
        $this->add_instruction('usedesigns', 'use designs');
        $this->add_instruction('useelements', 'use elements');
        $this->add_instruction('usedownloadpreview', 'use download preview');
        $this->add_instruction('useopenfile', 'use open file');
        $this->add_instruction('useexport', 'use export');
        $this->add_instruction('useshowcropzone', 'use show cropzone');
        $this->add_instruction('useshowsafezone', 'use show safe zone');
        $this->add_instruction('usestockphotos', 'use stock photos');
        $this->add_instruction('usetext', 'use text');
        $this->add_instruction('uselayers', 'use layers');
        $this->add_instruction('useqr', 'use QR');
        $this->add_instruction('usesettings', 'use settings');


        $instructionString = $this->parent->get_meta(self::EDITOR_INSTRUCTIONS_KEY);
        $instructionArray = $instructionString ? explode(' ', $instructionString) : [];

        foreach ($this->instructions as $key => $instruction) {
            $instruction->set_enabled(in_array($key, $instructionArray));
        }
    }

    public function add_instruction(string $key, string $label, bool $enabled = false, string $description = ''): self
    {
        $instruction = new PIE_Instruction($label, $enabled, $description);
        $this->instructions[$key] = $instruction;
        return $this;
    }

    public function remove_instruction(string $key): self
    {
        unset($this->instructions[$key]);
        return $this;
    }

    public function parse_instruction_array(array $instructions): self
    {
        foreach ($this->instructions as $key => $instruction) {
            $instruction->set_enabled(isset($instructions[$key]));
        }
        return $this;
    }

    public function parse_instruction_array_loop(array $instructions, int $loop): self
    {
        foreach ($this->instructions as $key => $instruction) {
            $instruction->set_enabled(isset($instructions[$key][$loop]));
        }
        return $this;
    }

    /**
     * returns an array key-value pairs representing editor instructions
     *
     * @return PIE_Instruction[]
     */
    public function get_instructions(): array
    {
        return $this->instructions;
    }

    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(
            self::EDITOR_INSTRUCTIONS_KEY,
            $this->get_instructions_string()
        );
    }

    public function get_instructions_string(): string
    {
        $arr = [];
        foreach ($this->instructions as $key => $instruction) {
            if ($instruction->is_enabled()) {
                $arr[] = $key;
            }
        }
        return implode(' ', $arr);
    }

    public function get_instruction_array(): array
    {
        $arr = [];
        foreach ($this->instructions as $key => $instruction) {
            if ($instruction->is_enabled()) {
                $arr[] = $key;
            }
        }
        return $arr;
    }
}
