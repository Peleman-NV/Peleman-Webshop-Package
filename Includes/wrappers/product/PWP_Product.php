<?php

declare(strict_types=1);

namespace PWP\includes\wrappers\product;

use PWP\includes\wrappers\PWP_Component;
use PWP\includes\wrappers\product\PWP_Tags;
use PWP\includes\wrappers\product\PWP_Images;
use PWP\includes\wrappers\product\PWP_Categories;
use WC_Product;

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

    public function type(): string
    {
        return $this->type ?: 'simple';
    }

    public function product_id(): ?int
    {
        if (empty($this->data->product_id)) {
            $this->data->product_id = wc_get_product_id_by_sku($this->sku());
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

    public function sku(): ?string
    {
        return $this->data->sku;
    }

    public function clear_sku(): void
    {
        unset($this->data->sku);
    }

    public function categories(): PWP_Categories
    {
        return new PWP_Categories($this->data->categories);
    }

    public function set_categories(PWP_Categories $categories): void
    {
        $this->data->categories = $categories->toArray();
    }

    public function tags(): PWP_Tags
    {
        return new PWP_Tags($this->data->tags);
    }

    public function set_tags(PWP_Tags $tags): void
    {
        $this->data->tags = $tags->toArray();
    }

    public function default_attributes(): PWP_Attributes
    {
        return new PWP_Attributes($this->data->default_attributes);
    }

    public function attributes(): PWP_Attributes
    {
        return new PWP_Attributes($this->data->attributes);
    }

    public function images(): PWP_Images
    {
        return new PWP_Images($this->data->images);
    }

    public function upsell_skus(): PWP_Skus
    {
        return new PWP_Skus($this->data->upsell_skus);
    }

    public function cross_sell_skus(): PWP_Skus
    {
        return new PWP_Skus($this->data->cross_sell_skus);
    }

    public function videos(): PWP_Videos
    {
        return new PWP_Videos($this->data->videos);
    }

    public function data_to_product(): WC_Product
    {
    }
}
