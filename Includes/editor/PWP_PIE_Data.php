<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\F2D\PWP_I_Meta_Property;
use WC_Product;

class PWP_PIE_Data extends PWP_Product_Meta_Data
{

    public string $templateId;
    public string $designId;
    public string $colorCode;
    public string $backgroundId;

    public const TEMPLATE_ID_KEY = 'pie_template_id';
    public const DESIGN_ID_KEY = 'pie_design_id';
    public const COLOR_CODE_KEY = 'pie_color_code';
    public const BACKGROUND_ID_KEY = 'pie_background_id';

    public const MY_EDITOR = 'PIE';

    public function __construct(WC_Product $parent)
    {
        parent::__construct($parent);

        $this->templateId = $this->parent->get_meta(self::TEMPLATE_ID_KEY, true) ?? '';
        $this->designId = $this->parent->get_meta(self::DESIGN_ID_KEY, true) ?? '';
        $this->colorCode = $this->parent->get_meta(self::COLOR_CODE_KEY, true) ?? '';
        $this->backgroundId =  $this->parent->get_meta(self::BACKGROUND_ID_KEY, true) ?? '';
    }

    public function get_template_id(): string
    {
        return $this->templateId;
    }

    public function set_template_id(string $id): void
    {
        $this->templateId = $id;
    }

    public function get_design_id(): string
    {
        return $this->designId;
    }

    public function set_design_id(string $code): void
    {
        $this->designId = $code;
    }

    public function get_color_code(): string
    {
        return $this->colorCode;
    }

    public function set_color_code(string $code): void
    {
        $this->colorCode = $code;
    }

    public function get_background_id(): string
    {
        return $this->backgroundId;
    }

    public function set_background_id(string $id): void
    {
        $this->backgroundId = $id;
    }

    public function get_variant_id(): string
    {
        return $this->variantId;
    }

    public function set_variant_id(string $variantId): void
    {
        $this->variantId = $variantId;
    }

    public function set_as_editor(): void
    {
        $this->editorId = "PIE";
    }

    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(self::TEMPLATE_ID_KEY, $this->templateId);
        $this->parent->update_meta_data(self::BACKGROUND_ID_KEY, $this->backgroundId,);
        $this->parent->update_meta_data(self::COLOR_CODE_KEY, $this->colorCode);
        $this->parent->update_meta_data(self::TEMPLATE_ID_KEY, $this->templateId);
        $this->parent->update_meta_data(self::DESIGN_ID_KEY, $this->designId);
    }
}
