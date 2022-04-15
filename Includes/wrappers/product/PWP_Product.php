<?php

declare(strict_types=1);

namespace PWP\includes\wrappers\product;

use WC_Product;
use PWP\includes\wrappers\PWP_Component;
use PWP\includes\wrappers\product\impl\PWP_Tags;
use PWP\includes\wrappers\product\impl\PWP_Images;
use PWP\includes\wrappers\product\impl\PWP_Attributes;
use PWP\includes\wrappers\product\impl\PWP_Categories;
use PWP\includes\wrappers\product\impl\PWP_Skus;
use PWP\includes\wrappers\product\impl\PWP_Videos;

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

    public function categories(): PWP_Component
    {
        return new PWP_Categories($this->data->categories);
    }

    public function set_categories(PWP_Categories $categories): void
    {
        $this->data->categories = $categories->toArray();
    }

    public function tags(): PWP_Component
    {
        return new PWP_Tags($this->data->tags);
    }

    public function set_tags(PWP_Tags $tags): void
    {
        $this->data->tags = $tags->toArray();
    }

    public function default_attributes(): PWP_Component
    {
        return new PWP_Attributes($this->data->default_attributes);
    }

    public function attributes(): PWP_Component
    {
        return new PWP_Attributes($this->data->attributes);
    }

    public function images(): PWP_Component
    {
        return new PWP_Images($this->data->images);
    }

    public function upsell_SKUs(): PWP_Component
    {
        return new PWP_SKUs($this->data->upsell_SKUs);
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
        return new PWP_SKUs($this->data->cross_sell_SKUs);
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
        $product = new WC_Product();

        $product->set_name($this->data->name);
        $product->set_reviews_allowed(false);

        if ($this->is_translation()) {
            $parentId = wc_get_product_id_by_sku($this->data->SKU);
            if (empty($parentId)) {
                throw new \Exception("Parent product not found (default language counterpart not found in database)", 400);
            }
        }
         if ( wc_get_product_id_by_sku($this->data->SKU) > 0) {
            throw new \Exception("product with this SKU already exists!", 400);
        }

        $product->set_SKU($this->data->SKU);
        $product->set_status($this->data->status);

        $product->set_catalog_visibility($this->data->visibility);

        if (!empty($this->data->parent_id)) {
            $product->set_parent_id($this->data->parent);
        }
        $product->set_price($this->price);
        $product->set_regular_price($this->data->regular_price);
        $product->set_sale_price($this->data->sale_price);

        $product->set_upsell_ids($this->upsell_ids());
        $product->set_cross_sell_ids($this->cross_sell_ids());

        $product->set_tag_ids($this->tags()->toArray());
        $product->set_category_ids($this->categories()->toArray());
        $product->set_attributes($this->attributes()->toArray());
        $product->set_default_attributes($this->default_attributes()->toArray());

        $product->set_image_id($this->data->main_image_id);
        $product->set_gallery_image_ids($this->images()->toArray());

        $product->set_meta_data(array('customizable' => $this->data->customizable));
        $product->set_meta_data(array('template-id' => $this->data->template_id));
        $product->set_meta_data(array('template-variant-id' => $this->data->template_variant_id));
        if (!empty($this->data->lang)) {
            $product->set_meta_data(array('lang' => $this->data->lang));
        }
        //...

        $id = $product->save();

        if ($id <= 0) {
            throw new \Exception("something went wrong when trying to save a new product!", 500);
        }

        return $product;
    }
}
