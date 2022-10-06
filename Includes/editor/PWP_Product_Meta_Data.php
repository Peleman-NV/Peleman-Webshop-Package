<?php

declare(strict_types=1);

namespace PWP\includes\editor;

class PWP_Product_Meta_Data extends PWP_Product_Meta
{

    private bool $customizable;
    private bool $usePDFContent;
    private string $editorId;
    private string $customAddToCartLabel;

    private int $pdfHeight;
    private int $pdfWidth;
    private int $maxPages;
    private int $minPages;
    private float $pricePerPage;

    public ?PWP_Product_PIE_Data $pieData;
    public ?PWP_Product_IMAXEL_Data $imaxelData;

    public function __construct(\WC_Product $product)
    {
        parent::__construct($product);

        $this->editorId             = $this->parent->get_meta(PWP_Keys::EDITOR_ID_KEY) ?: '';
        $this->customizable         = !empty($this->editorId);
        $this->usePDFContent        = boolval($this->parent->get_meta(PWP_Keys::USE_PDF_CONTENT_KEY)) ?: false;
        $this->customAddToCartLabel = $this->parent->get_meta(PWP_Keys::CUSTOM_LABEL_KEY) ?: '';

        $this->pdfHeight            = (int)$this->parent->get_meta(PWP_Keys::PDF_HEIGHT_KEY) ?: 0;
        $this->pdfWidth             = (int)$this->parent->get_meta(PWP_Keys::PDF_WIDTH_KEY) ?: 0;
        $this->maxPages             = (int)$this->parent->get_meta(PWP_Keys::PDF_MAX_PAGES_KEY) ?: -1;
        $this->minPages             = (int)$this->parent->get_meta(PWP_Keys::PDF_MIN_PAGES_KEY) ?: 1;
        $this->pricePerPage         = (float)$this->parent->get_meta(PWP_Keys::PDF_PRICE_PER_PAGE_KEY) ?: 0;

        $this->pieData              = null;
        $this->imaxelData           = null;
    }

    public function set_uses_pdf_content(bool $usePDFContent): self
    {
        $this->usePDFContent = $usePDFContent;
        return $this;
    }

    public function uses_pdf_content(): bool
    {
        return $this->usePDFContent;
    }

    public function is_customizable(): bool
    {
        return $this->customizable;
    }

    /**
     * set editor ID for product.
     *
     * @param string $editorId
     * @return self
     */
    public function set_editor(string $editorId): self
    {
        $this->editorId = $editorId;
        $this->customizable = !empty($editorId);
        return $this;
    }
    public function get_editor_id(): string
    {
        return $this->editorId;
    }

    public function set_custom_add_to_cart_label(string $label): self
    {
        $this->customAddToCartLabel = $label;
        return $this;
    }
    public function get_custom_add_to_cart_label(): string
    {
        return $this->customAddToCartLabel;
    }


    public function set_pdf_width(int $width): self
    {
        $this->pdfWidth = $width;
        return $this;
    }
    public function get_pdf_width(): ?int
    {
        return $this->pdfWidth;
    }


    public function set_pdf_height(int $height): self
    {
        $this->pdfHeight = $height;
        return $this;
    }
    public function get_pdf_height(): ?int
    {
        return $this->pdfHeight;
    }


    public function set_pdf_min_pages(int $pages): self
    {
        $this->minPages = $pages;
        return $this;
    }
    public function get_pdf_min_pages(): ?int
    {
        return $this->minPages;
    }


    public function set_pdf_max_pages(int $pages): self
    {
        $this->maxPages = $pages;
        return $this;
    }
    public function get_pdf_max_pages(): ?int
    {
        return $this->maxPages;
    }


    public function set_price_per_page(float $pricePerPage): self
    {
        $this->pricePerPage = $pricePerPage;
        return $this;
    }
    public function get_price_per_page(): float
    {
        return $this->pricePerPage ?: 0.0;
    }


    public function imaxel_data(): PWP_Product_IMAXEL_Data
    {
        if ($this->imaxelData === null) {
            $this->imaxelData = new PWP_Product_IMAXEL_Data($this->get_parent());
        }
        return $this->imaxelData;
    }

    public function pie_data(): PWP_Product_PIE_Data
    {
        if ($this->pieData === null) {
            $this->pieData = new PWP_Product_PIE_Data($this->get_parent());
        }
        return $this->pieData;
    }


    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(PWP_Keys::USE_PDF_CONTENT_KEY, $this->usePDFContent ? 1 : 0);
        $this->parent->update_meta_data(PWP_Keys::EDITOR_ID_KEY, $this->editorId);
        $this->parent->update_meta_data(PWP_Keys::CUSTOM_LABEL_KEY, $this->customAddToCartLabel);

        //TODO: make PDF editor data its own object
        //but this will do for now
        $this->parent->update_meta_data(PWP_Keys::PDF_HEIGHT_KEY, $this->pdfHeight);
        $this->parent->update_meta_data(PWP_Keys::PDF_WIDTH_KEY, $this->pdfWidth);
        $this->parent->update_meta_data(PWP_Keys::PDF_MIN_PAGES_KEY, $this->minPages);
        $this->parent->update_meta_data(PWP_Keys::PDF_MAX_PAGES_KEY, $this->maxPages);
        $this->parent->update_meta_data(PWP_Keys::PDF_PRICE_PER_PAGE_KEY, $this->pricePerPage);

        $this->imaxel_data()->update_meta_data();
        $this->pie_data()->update_meta_data();
    }

    public function save_meta_data(): void
    {
        $this->parent->save();
        $this->imaxel_data()->save_meta_data();
        $this->pie_data()->save_meta_data();
        $this->parent->save_meta_data();
    }
}
