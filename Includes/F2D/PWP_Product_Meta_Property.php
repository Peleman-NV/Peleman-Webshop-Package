<?php

declare(strict_types=1);

namespace PWP\includes\F2D;

use PWP\includes\wrappers\PWP_Product_Meta_Data;
use WC_Privacy;
use WC_Product;
use WP_Post;

class PWP_Product_Meta_Property implements PWP_I_Meta_Property

{
    private \WC_Product $owner;
    private string $meta_key;
    private string $label_text;
    private string $description;
    private string $type;

    private function __construct(\WC_Product $owner, string $meta_key, string $type = 'text')
    {
        $this->owner = $owner;
        $this->meta_key = $meta_key;
        $this->type = $type;
        $this->label_text = '';
        $this->description = '';
    }

    public static function new_text(\WC_Product $owner, string $meta_key): self
    {
        return new PWP_Product_Meta_Property($owner, $meta_key, 'string');
    }

    public static function new_number(\WC_Product $owner, string $meta_key): self
    {
        return new PWP_Product_Meta_Property($owner, $meta_key, 'number');
    }

    public static function new_bool(\WC_Product $owner, string $meta_key): self
    {
        return new PWP_Product_Meta_Property($owner, $meta_key, 'bool');
    }

    public function set_label_text(string $text): self
    {
        $this->label_text = $text;
        return $this;
    }

    public function set_description(string $text): self
    {
        $this->description = $text;
        return $this;
    }


    public function get_label_text(): string
    {
        return $this->label_text;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_value(): mixed
    {
        return $this->owner->get_meta($this->meta_key);
    }

    public function set_value(string $value): mixed
    {
        return $this->owner->update_meta_data($this->meta_key, $value);
    }

    public function get_key(): string
    {
        return $this->meta_key;
    }
}
