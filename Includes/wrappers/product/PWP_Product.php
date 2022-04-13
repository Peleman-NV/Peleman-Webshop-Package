<?php

declare(strict_types=1);

namespace PWP\includes\wrappers\product;

use PWP\includes\wrappers\PWP_Component;
use PWP\includes\wrappers\product\PWP_Tags;
use PWP\includes\wrappers\product\PWP_Images;
use PWP\includes\wrappers\product\PWP_Categories;

class PWP_Product extends PWP_Component
{
    public function id(): int
    {
        return (int)$this->data->id;
    }

    public function lang(): ?string
    {
        return $this->data->lang;
    }

    public function sku(): ?string
    {
        return $this->data->sku;
    }

    public function categories(): PWP_Categories
    {
        return new PWP_Categories($this->data->categories);
    }

    public function tags(): PWP_Tags
    {
        return new PWP_Tags($this->data->tags);   
    }

    public function default_attributes() : PWP_Attributes
    {
        return new PWP_Attributes($this->data->default_attributes);
    }

    public function attributes() : PWP_Attributes
    {
        return new PWP_Attributes($this->data->attributes);
    }

    public function images() : PWP_Images
    {
        return new PWP_Images($this->data->images);
    }

    public function upsell_skus() : PWP_Skus
    {
        return new PWP_Skus($this->data->upsell_skus);
    }

    public function cross_sell_skus(): PWP_Skus
    {
        return new PWP_Skus($this->data->cross_sell_skus);
    }

    public function videos() : PWP_Videos
    {
        return new PWP_Videos($this->data->videos);
    }

    

}
