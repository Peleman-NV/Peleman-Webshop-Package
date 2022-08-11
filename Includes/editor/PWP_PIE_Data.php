<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use Exception;
use PWP\includes\F2D\PWP_I_Meta_Property;
use WC_Product;
use WP_Post;

class PWP_PIE_Data implements PWP_I_Meta_Property
{
    private WC_Product $parent;

    public bool $customizable;
    public bool $usePDFContent;
    public string $templateId;
    public string $designId;
    public string $colorCode;
    public string $backgroundId;

    public const CUSTOMIZABLE = 'pie_customizable';
    public const USE_PDF_CONTENT = 'pie_use_pdf_content';
    public const TEMPLATE_ID = 'pie_template_id';
    public const DESIGN_ID = 'pie_design_id';
    public const COLOR_CODE = 'pie_color_code';
    public const BACKGROUND_ID = 'pie_background_id';

    public function __construct(int $productID)
    {
        $product = wc_get_product($productID);
        if (is_null($product)) {
            //TODO: write custom exception class
            throw new Exception("Product with ID {$productID} not found. Id not valid or object is not a product.");
        }

        $this->parent = wc_get_product($productID);

        $this->customizable = boolval($product->get_meta(self::CUSTOMIZABLE, true) ?? false);
        $this->usePDFContent = boolval($product->get_meta(self::USE_PDF_CONTENT, true) ?? false);
        $this->templateId = $product->get_meta(self::TEMPLATE_ID, true) ?? '';
        $this->designId = $product->get_meta(self::DESIGN_ID, true) ?? '';
        $this->colorCode = $product->get_meta(self::COLOR_CODE, true) ?? '';
        $this->backgroundId =  $product->get_meta(self::BACKGROUND_ID, true) ?? '';
    }

    public function get_parent(): WC_Product
    {
        return $this->parent;
    }

    public function get_is_customizable(): bool
    {
        return $this->customizable;
    }

    public function set_customizable(bool $customizable): void
    {
        $this->customizable = $customizable;
    }

    public function get_uses_pdf_content(): bool
    {
        return $this->usePDFContent;
    }

    public function set_uses_pdf_content(bool $usePDFContent): void
    {
        $this->usePDFContent = $usePDFContent;
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

    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(self::CUSTOMIZABLE, $this->customizable ? 1 : 0, true);
        $this->parent->update_meta_data(self::USE_PDF_CONTENT, $this->useContent ? 1 : 0, true);
        $this->parent->update_meta_data(self::BACKGROUND_ID, $this->backgroundId, true);
        $this->parent->update_meta_data(self::COLOR_CODE, $this->colorCode, true);
        $this->parent->update_meta_data(self::TEMPLATE_ID, $this->templateId, true);
        $this->parent->update_meta_data(self::DESIGN_ID, $this->designId, true);

        $this->parent->save_meta_data();
    }
}
