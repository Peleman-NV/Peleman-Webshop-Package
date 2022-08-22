<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\F2D\PWP_I_Meta_Property;
use WC_Product;

class PWP_IMAXEL_Data extends PWP_Product_Meta_Data
{
    public const TEMPLATE_ID_KEY = 'imaxel_template_id';
    public const VARIANT_ID_KEY = 'imaxel_variant_id';

    public const MY_EDITOR = 'IMAXEL';

    public function __construct(WC_Product $parent)
    {
        parent::__construct($parent);

        $this->templateId = $this->parent->get_meta(self::TEMPLATE_ID_KEY, true) ?? '';
        $this->variantId = $this->parent->get_meta(self::VARIANT_ID_KEY, true) ?? '';
    }

    public function get_template_id(): string
    {
        return $this->templateId;
    }

    public function set_template_id(string $templateId): void
    {
        $this->templateId = $templateId;
    }

    public function get_variant_id(): string
    {
        return $this->variantId;
    }

    public function set_variant_id(string $variantId): void
    {
        $this->variantId = $variantId;
    }

    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(self::TEMPLATE_ID_KEY, $this->templateId);
        $this->parent->update_meta_data(self::VARIANT_ID_KEY, $this->variantId);
    }
}