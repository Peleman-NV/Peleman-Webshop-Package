<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use WC_Product;

/**
 * Metadata container for IMAXEL specific product data
 * @deprecated 1.0.0 PWP no longer supports IMAXEL
 */
class Product_IMAXEL_Data extends Product_Meta
{
    public const MY_EDITOR              = 'IMAXEL';
    public const IMAXEL_TEMPLATE_ID_KEY = 'imaxel_template_id';
    public const IMAXEL_VARIANT_ID_KEY  = 'imaxel_variant_id';

    private string $templateId;
    private string $variantId;

    public function __construct(WC_Product $parent)
    {
        parent::__construct($parent);

        $this->templateId = $this->parent->get_meta(self::IMAXEL_TEMPLATE_ID_KEY, true) ?? '';
        $this->variantId = $this->parent->get_meta(self::IMAXEL_VARIANT_ID_KEY, true) ?? '';
    }

    public function get_template_id(): string
    {
        return $this->templateId;
    }

    public function set_template_id(string $templateId): self
    {
        $this->templateId = $templateId;
        return $this;
    }

    public function get_variant_id(): string
    {
        return $this->variantId;
    }

    public function set_variant_id(string $variantId): self
    {
        $this->variantId = $variantId;
        return $this;
    }

    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(self::IMAXEL_TEMPLATE_ID_KEY, $this->templateId);
        $this->parent->update_meta_data(self::IMAXEL_VARIANT_ID_KEY, $this->variantId);
    }
}
