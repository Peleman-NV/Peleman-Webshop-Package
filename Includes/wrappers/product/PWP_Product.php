<?php

declare(strict_types=1);

namespace PWP\includes\wrappers\product;

use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\handlers\PWP_Tag_Handler;
use WC_Product;
use PWP\includes\wrappers\PWP_Component;
use PWP\includes\wrappers\product\impl\PWP_Images;
use PWP\includes\wrappers\product\impl\PWP_Videos;
use PWP\includes\wrappers\product\impl\PWP_Attribute;
use WP_Term;

class PWP_Product extends PWP_Component
{

    public function id(): int
    {
        return (int)$this->data->id;
    }

    public function is_new_product(): bool
    {
        return empty($this->product_id());
    }

    public function is_SKU_unique(): bool
    {
        return 0 >= wc_get_product_id_by_SKU($this->data->SKU);
    }

    public function type(): string
    {
        return $this->type ?: 'simple';
    }

    public function product_id(): ?int
    {
        if (empty($this->data->product_id)) {
            $this->data->product_id = wc_get_product_id_by_SKU($this->SKU());
        }
        return (int)$this->data->product_id;
    }

    public function allow_reviews(): void
    {
        $this->data->reviews_allowed = 1;
    }

    public function disallow_reviews(): void
    {
        $this->data->reviews_allowed = 0;
    }

    public function is_parent_product(): bool
    {
        return empty($this->data->lang);
    }

    public function set_is_translation_of(int $id): void
    {
        $this->data->translation_of = $id;
    }

    public function lang(): ?string
    {
        return $this->data->lang;
    }

    public function SKU(): ?string
    {
        return $this->data->SKU;
    }

    public function clear_SKU(): void
    {
        unset($this->data->SKU);
    }

    public function categories(): array
    {
        return (array)$this->data->categories;
    }

    public function category_ids(): array
    {
        //FIXME: use new service based architecture.
        // $ids = array();
        // $handler =  new PWP_Category_Handler();
        // foreach ($this->categories() as $slug) {
        //     $category = $handler->get_item_by_slug($slug);
        //     if (!empty($category)) {
        //         $ids[] = $category;
        //     }
        // }

        return array();
    }

    public function set_categories(array $categories): void
    {
        $this->data->categories = $categories;
    }

    public function tags(): array
    {
        return (array)$this->data->tags;
    }

    public function tag_ids(): array
    {
        //FIXME: use new service based architecture
        // $ids = array();
        // $handler =  new PWP_Tag_Handler();
        // foreach ($this->tags() as $slug) {
        //     echo ($slug);
        //     $tag = $handler->get_item_by_slug($slug);
        //     if ($tag instanceof WP_Term) {
        //         $ids[] = $tag->term_id;
        //     }
        // }

        // return $ids;
        return array();
    }

    public function set_tags(array $tags): void
    {
        $this->data->tags = $tags;
    }

    public function default_attributes(): PWP_Component
    {
        return new PWP_Attribute($this->data->default_attributes);
    }

    public function attributes(): PWP_Component
    {
        return new PWP_Attribute($this->data->attributes);
    }

    public function images(): PWP_Component
    {
        return new PWP_Images($this->data->images);
    }

    public function upsell_SKUs(): PWP_Component
    {
        return $this->data->upsell_SKUs;
    }

    public function upsell_ids(): array
    {
        //TODO: complete logic
        return array();
    }

    public function is_translation(): bool
    {
        return !empty($this->data->lang);
    }

    public function cross_sell_SKUs(): PWP_Component
    {
        return $this->data->cross_sell_SKUs;
    }

    public function cross_sell_ids(): array
    {
        //TODO: complete logic
        return array();
    }

    public function videos(): PWP_Component
    {
        return new PWP_Videos($this->data->videos);
    }

    public function save_to_product(): WC_Product
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }
}
