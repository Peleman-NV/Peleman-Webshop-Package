<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

use PWP\includes\handlers\PWP_I_Slug_Handler;

class PWP_Category extends PWP_Component
{
    public function __construct(string $name, string $slug = '', string $description = '', array $data = [])
    {
        parent::__construct($data);
        $this->taxonomy = 'product_cat';
        $this->beautyName = 'product category';
        $this->elementType =  'tax_product_cat';

        $this->name = $name;
        $this->slug = $slug ?: $this->generate_slug($name, $this->data->language_code);
        $this->description = $description;
    }

    public function save_new_term(): \WP_Term
    {
        $parent = $this->find_parent();

        $termData =  wp_insert_term($this->data->name, $this->taxonomy, array(
            'slug' => $this->slug,
            'description' => $this->data->description ?: '',
            'parent' => $parent->term_id ?: 0,
        ));

        if ($termData instanceof \WP_Error) {
            throw new \Exception($termData->get_error_message(), $termData->get_error_code());
        }

        return $this->get_item($termData['term_id']);
    }

    final private function generate_slug(string $name, ?string $lang = null): string
    {
        $slug = str_replace(' ', '-', strtolower($name));

        if (!empty($lang)) {
            $slug .= "-{$lang}";
        }
        $this->data->slug = $slug;
        return $slug;
    }

    final private function find_parent(): ?\WP_Term
    {
        $id = (int)$this->data->parent_id;
        $slug = $this->data->slug;

        if (empty($id) && empty($slug)) return null;

        if (!empty($id) || 0 >= $id) {
            $parent = $this->get_item($id);
            if (!empty($parent)) {
                return $parent;
            }
        }

        if (!empty($slug)) {
            $parent = $this->get_item_by_slug($slug);
            if (!empty($parent)) {
                return $parent;
            }
        }

        return null;
    }

    final function get_item(int $id, array $args = []): ?\WP_Term
    {
        $term = get_term($id, $this->taxonomy, OBJECT);
        if ($term instanceof \WP_Term) {
            return $term;
        }
        return null;
    }

    final public function get_item_by_slug(string $slug): ?\WP_Term
    {
        $termData = get_term_by('slug', $slug, $this->taxonomy);
        return !empty($termData) ? $termData : null;
    }
}
