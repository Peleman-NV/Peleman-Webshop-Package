<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\F2D\PWP_I_Meta_Property;

class PWP_Editor_Data extends PWP_Product_Meta_Data
{
    public const CUSTOMIZABLE = 'pwp_customizable';
    public const USE_PDF_CONTENT = 'pwp_use_pdf_content';
    public const EDITOR_ID = 'pwp_editor_id';

    private bool $customizable;
    private bool $usePDFContent;
    /**
     * ID of the editor the parent uses for customization
     *
     * @var string
     */
    private string $editorId;

    public ?PWP_PIE_Data $pieData;
    public ?PWP_IMAXEL_Data $imaxelData;

    public function __construct(int $productId)
    {
        $product = wc_get_product($productId);
        if (is_null($product)) {
            //TODO: write custom exception class
            throw new \Exception("Product with ID {$productId} not found. Id not valid or object is not a product.");
        }

        parent::__construct($product);
        $this->customizable = boolval($this->parent->get_meta(self::CUSTOMIZABLE, true) ?? false);
        $this->usePDFContent = boolval($this->parent->get_meta(self::USE_PDF_CONTENT, true) ?? false);
        $this->editorId = $this->parent->get_meta(self::EDITOR_ID, true);

        $this->pieData = null;
        $this->imaxelData = null;
    }

    public function uses_pdf_content(): bool
    {
        return $this->usePDFContent;
    }

    public function set_uses_pdf_content(bool $usePDFContent): void
    {
        $this->usePDFContent = $usePDFContent;
    }

    public function get_editor_id(): string
    {
        return $this->get_editor_id;
    }

    public function is_customizable(): bool
    {
        return $this->customizable;
    }

    public function set_customizable(bool $customizable): void
    {
        $this->customizable = $customizable;
    }

    public function set_editor(string $editorId): void
    {
        $this->editorId = $editorId;
    }

    public function imaxel_data(): PWP_IMAXEL_Data
    {
        if ($this->imaxelData === null) {
            $this->imaxelData = new PWP_IMAXEL_Data($this->parent);
        }
        return $this->imaxelData;
    }

    public function pie_data(): PWP_PIE_Data
    {
        if ($this->pieData === null) {
            $this->pieData = new PWP_PIE_Data($this->parent);
        }
        return $this->pieData;
    }

    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(self::CUSTOMIZABLE, $this->customizable ? 1 : 0);
        $this->parent->update_meta_data(self::USE_PDF_CONTENT, $this->usePDFContent ? 1 : 0);


        $this->imaxel_data()->update_meta_data();
        $this->pie_data()->update_meta_data();
    }

    public function save_meta_data(): void
    {
        parent::save_meta_data();
        $this->imaxel_data()->update_meta_data();
        $this->pie_data()->update_meta_data();
    }
}
