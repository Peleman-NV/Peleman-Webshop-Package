<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

use PWP\includes\exceptions\PWP_Invalid_Input_Exception;

class PWP_Term_Data extends PWP_Component
{
    final protected function generate_slug(string $name, ?string $lang = null): string
    {
        $slug = str_replace(' ', '_', strtolower($name));

        if (!empty($lang)) {
            $slug .= "-{$lang}";
        }
        $this->data->slug = $slug;
        $this->data->parent = $this->data->parent_id;
        unset($this->data->parent_id);
        return $slug;
    }

    final public function get_seo_data(): ?PWP_SEO_Data
    {
        if (!isset($this->data->seo)) return null;
        return new PWP_SEO_Data($this->data->seo);
    }

    final public function get_translation_data(): PWP_Translation_Data
    {
        return new PWP_Translation_Data(
            array(
                'english_slug' => $this->data->english_slug,
                'language_code' => $this->data->language_code,
            )
        );
    }

    final public function has_translation_data(): bool
    {
        return ($this->data->english_slug && $this->data->language_code);
    }

    final public function get_name(): string
    {
        return $this->data->name;
    }

    final public function get_slug(): string
    {
        if (empty($this->data->slug))
            $this->data->slug = $this->generate_slug($this->data->name, $this->data->language_code);
        return $this->data->slug;
    }

    final public function get_description(): string
    {
        return $this->data->description ?: '';
    }

    final public function get_parent_slug(): string
    {
        return $this->data->parent_slug ?: '';
    }

    final public function get_parent_id(): int
    {
        return (int)($this->data->parent ?: '');
    }

    final public function set_parent(int $id): void
    {
        $this->data->parent = $id;
    }

    final public function set_parent_slug(string $slug): void
    {
        $this->data->parent_slug = $slug;
    }
}
