<?php

declare(strict_types=1);

namespace PWP\includes\editor;

class Product_Meta_Data extends Product_Meta
{
    #region constants
    public const EDITOR_ID_KEY          = 'pwp_editor_id';
    public const CUSTOM_LABEL_KEY       = 'custom_variation_add_to_cart_label';
    public const F2D_ARTICLE_CODE       = 'f2d_artcd';

    public const UNIT_AMOUNT            = 'cart_units';
    public const UNIT_PRICE             = 'cart_price';
    public const UNIT_CODE              = 'unit_code';

    public const USE_PDF_CONTENT_KEY    = 'pdf_upload_required';
    public const PDF_SIZE_CHECK         = 'pdf_size_check';
    public const PDF_HEIGHT_KEY         = 'pdf_height_mm';
    public const PDF_WIDTH_KEY          = 'pdf_width_mm';
    public const PDF_MAX_PAGES_KEY      = 'pdf_max_pages';
    public const PDF_MIN_PAGES_KEY      = 'pdf_min_pages';
    public const PDF_PRICE_PER_PAGE_KEY = 'price_per_page';

    public const OVERRIDE_CART_THUMB    = 'pwp_override_cart_thumb';

    public const VAR_PREFIX = "var_";
    #endregion

    #region variables
    private bool $customizable;
    private string $editorId;
    private string $customAddToCartLabel;

    private int $cartUnits;
    private float $cartPrice;
    private string $unitCode;
    private string $articleCode;

    private bool $usePDFContent = false;
    private bool $pdfSizeCheckEnabled = true;
    private int $pdfHeight;
    private int $pdfWidth;
    private int $maxPages;
    private int $minPages;
    private float $pricePerPage;

    private bool $overrideThumb;

    public ?Product_PIE_Data $pieData;
    #endregion

    public function __construct(\WC_Product $product)
    {
        parent::__construct($product);

        $this->editorId             = (string)$this->parent->get_meta(self::EDITOR_ID_KEY) ?: '';
        $this->customizable         = !empty($this->editorId);
        $this->usePDFContent        = $this->parse_nonbool_value($this->parent->get_meta(self::USE_PDF_CONTENT_KEY));
        $this->pdfSizeCheckEnabled = $this->parse_nonbool_value($this->parent->get_meta(self::PDF_SIZE_CHECK));
        $this->customAddToCartLabel = (string)$this->parent->get_meta(self::CUSTOM_LABEL_KEY) ?: '';

        $this->cartUnits            = (int)$this->parent->get_meta(self::UNIT_AMOUNT) ?: 1;
        $this->cartPrice            = (float)$this->parent->get_meta(self::UNIT_PRICE) ?: 0.00;
        $this->unitCode             = (string)$this->parent->get_meta(self::UNIT_CODE) ?: '';
        $this->articleCode          = (string)$this->parent->get_meta(self::F2D_ARTICLE_CODE ?: '');

        //defaults to A4 height
        $this->pdfHeight            = (int)$this->parent->get_meta(self::PDF_HEIGHT_KEY) ?: 297;
        //defaults to A4 width
        $this->pdfWidth             = (int)$this->parent->get_meta(self::PDF_WIDTH_KEY) ?: 210;
        $this->maxPages             = (int)$this->parent->get_meta(self::PDF_MAX_PAGES_KEY) ?: 1;
        $this->minPages             = (int)$this->parent->get_meta(self::PDF_MIN_PAGES_KEY) ?: 1;
        $this->pricePerPage         = (float)$this->parent->get_meta(self::PDF_PRICE_PER_PAGE_KEY) ?: 0.000;

        $this->overrideThumb        = $this->parse_nonbool_value($this->parent->get_meta(self::OVERRIDE_CART_THUMB)) ?: false;
        $this->pieData              = null;
    }

    #region getters-setters
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

    public function is_editable(): bool
    {

        return !empty($this->pie_data()->get_template_id());
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

    public function set_unit_amount(int $units): self
    {
        $this->cartUnits = max(1, $units);
        return $this;
    }
    public function get_unit_amount(): int
    {
        return $this->cartUnits;
    }

    public function set_unit_price(float $price): self
    {
        $this->cartPrice = $price;
        return $this;
    }
    public function get_unit_price(): float
    {
        return $this->cartPrice;
    }

    public function set_unit_code(string $code): self
    {
        $this->unitCode = $code;
        return $this;
    }
    public function get_unit_code(): string
    {
        return $this->unitCode;
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

    public function set_override_thumbnail(bool $override = true): self
    {
        $this->overrideThumb = $override;
        return $this;
    }

    public function get_override_thumbnail(): bool
    {
        return $this->overrideThumb;
    }

    public function get_f2d_article_code(): string
    {
        return $this->articleCode;
    }

    public function set_f2d_article_code(string $code): self
    {
        $this->articleCode = $code;
        return $this;
    }

    public function pie_data(): Product_PIE_Data
    {
        if ($this->pieData === null) {
            $this->pieData = new Product_PIE_Data($this->get_parent());
        }
        return $this->pieData;
    }

    public function set_pdf_size_check_enabled(bool $disabled = false): self
    {
        //we invert the input so working with the value in the front-end
        //makes more sense.
        $this->pdfSizeCheckEnabled = !$disabled;
        return $this;
    }

    public function pdf_size_check_enabled(): bool
    {
        //we invert the input so working with the value in the front-end
        //makes more sense.
        return !$this->pdfSizeCheckEnabled;
    }
    #endregion

    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(self::USE_PDF_CONTENT_KEY, $this->usePDFContent ? 1 : 0);
        $this->parent->update_meta_data(self::PDF_SIZE_CHECK, $this->pdfSizeCheckEnabled ? 1 : 0);
        $this->parent->update_meta_data(self::EDITOR_ID_KEY, $this->editorId);
        $this->parent->update_meta_data(self::CUSTOM_LABEL_KEY, $this->customAddToCartLabel);

        $this->parent->update_meta_data(self::UNIT_AMOUNT, $this->cartUnits);
        $this->parent->update_meta_data(self::UNIT_PRICE, $this->cartPrice);
        $this->parent->update_meta_data(self::UNIT_CODE, $this->unitCode);
        $this->parent->update_meta_data(self::F2D_ARTICLE_CODE, $this->articleCode);

        //TODO: make PDF editor data its own object
        //but this will do for now
        $this->parent->update_meta_data(self::PDF_HEIGHT_KEY, $this->pdfHeight);
        $this->parent->update_meta_data(self::PDF_WIDTH_KEY, $this->pdfWidth);
        $this->parent->update_meta_data(self::PDF_MIN_PAGES_KEY, $this->minPages);
        $this->parent->update_meta_data(self::PDF_MAX_PAGES_KEY, $this->maxPages);
        $this->parent->update_meta_data(self::PDF_PRICE_PER_PAGE_KEY, $this->pricePerPage);
        $this->parent->update_meta_data(self::OVERRIDE_CART_THUMB, $this->overrideThumb);

        $this->pie_data()->update_meta_data();
    }

    public function save_meta_data(): void
    {
        $this->parent->save();
        $this->pie_data()->save_meta_data();
        $this->parent->save_meta_data();
    }

    /**
     * Helper method to parse non-boolean values passed through legacy API.
     *
     * @param mixed $val
     * @return bool
     */
    private function parse_nonbool_value($val): bool
    {
        switch ($val) {
            case 'no':
            case 'false':
            case '0':
            case false:
                return false;
            default:
                return true;
        }
    }
}
