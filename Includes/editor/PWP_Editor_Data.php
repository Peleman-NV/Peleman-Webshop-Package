<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use WC_Product;

class PWP_Editor_Data extends PWP_Product_Meta_Data
{
    public const CUSTOMIZABLE = 'pwp_customizable';
    public const USE_PDF_CONTENT = 'pwp_use_pdf_content';
    public const EDITOR_ID = 'pwp_editor_id';

    private bool $customizable;
    private bool $usePDFContent;
    private string $editorId;

    private int $pdfHeight;
    private int $pdfWidth;
    private int $maxPages;
    private int $minPages;
    private float $pricePerPage;


    public ?PWP_PIE_Data $pieData;
    public ?PWP_IMAXEL_Data $imaxelData;

    public function __construct(WC_Product $product)
    {
        parent::__construct($product);

        $this->customizable         = boolval($this->parent->get_meta(self::CUSTOMIZABLE)) ?: false;
        $this->editorId             = $this->parent->get_meta(self::EDITOR_ID) ?: '';
        $this->usePDFContent        = boolval($this->parent->get_meta(self::USE_PDF_CONTENT)) ?: false;

        $this->pdfHeight            = (int)$this->parent->get_meta('pdf_height_mm') ?: 0;
        $this->pdfWidth             = (int)$this->parent->get_meta('pdf_width_mm') ?: 0;
        $this->maxPages             = (int)$this->parent->get_meta('pdf_max_pages') ?: -1;
        $this->minPages             = (int)$this->parent->get_meta('pdf_min_pages') ?: 1;
        $this->pricePerPage         = (float)$this->parent->get_meta('price_per_page') ?: 0;

        $this->pieData              = null;
        $this->imaxelData           = null;
    }

    public function set_uses_pdf_content(bool $usePDFContent): void
    {
        $this->usePDFContent = $usePDFContent;
    }
    public function uses_pdf_content(): bool
    {
        return $this->usePDFContent;
    }


    public function set_customizable(bool $customizable): void
    {
        $this->customizable = $customizable;
    }
    public function is_customizable(): bool
    {
        return $this->customizable;
    }


    public function set_editor(string $editorId): void
    {
        $this->editorId = $editorId;
    }
    public function get_editor_id(): string
    {
        return $this->editorId;
    }


    public function set_pdf_width(int $width): void
    {
        $this->pdf_width_mm = $width;
    }
    public function get_pdf_width(): ?int
    {
        return $this->pdfWidth;
    }


    public function set_pdf_height(int $height): void
    {
        $this->pdf_height_mm = $height;
    }
    public function get_pdf_height(): ?int
    {
        return $this->pdfHeight;
    }


    public function set_pdf_min_pages(int $pages): void
    {
        $this->minPages = $pages;
    }
    public function get_pdf_min_pages(): ?int
    {
        return $this->minPages;
    }


    public function set_pdf_max_pages(int $pages): void
    {
        $this->maxPages = $pages;
    }
    public function get_pdf_max_pages(): ?int
    {
        return $this->maxPages;
    }


    public function set_price_per_page(float $pricePerPage): void
    {
        $this->pricePerPage = $pricePerPage;
    }
    public function get_price_per_page(): ?float
    {
        return $this->pricePerPage;
    }


    public function imaxel_data(): PWP_IMAXEL_Data
    {
        if ($this->imaxelData === null) {
            $this->imaxelData = new PWP_IMAXEL_Data($this->get_parent());
        }
        return $this->imaxelData;
    }

    public function pie_data(): PWP_PIE_Data
    {
        if ($this->pieData === null) {
            $this->pieData = new PWP_PIE_Data($this->get_parent());
        }
        return $this->pieData;
    }


    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(self::CUSTOMIZABLE, $this->customizable ? 1 : 0);
        $this->parent->update_meta_data(self::USE_PDF_CONTENT, $this->usePDFContent ? 1 : 0);
        $this->parent->update_meta_data(self::EDITOR_ID, $this->editorId);

        $this->parent->update_meta_data('pdf_height_mm', $this->pdfHeight);
        $this->parent->update_meta_data('pdf_width_mm', $this->pdfWidth);
        $this->parent->update_meta_data('pdf_min_pages', $this->minPages);
        $this->parent->update_meta_data('pdf_max_pages', $this->maxPages);
        $this->parent->update_meta_data('price_per_page', $this->pricePerPage);

        $this->imaxel_data()->update_meta_data();
        $this->pie_data()->update_meta_data();
    }

    public function save(): void
    {
        $this->parent->save();
        $this->imaxel_data()->save();
        $this->pie_data()->save();
    }
}
