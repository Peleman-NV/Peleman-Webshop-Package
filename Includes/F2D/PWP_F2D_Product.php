<?php

declare(strict_types=1);

namespace PWP\Includes\F2D;

use WC_Product;

const META_F2D_SKU_COMPONENT = 'f2d_sku_components';
const META_F2D_SKU_ARTICLE_CODE = 'f2d_artcd';

const META_PIE_CUSTOMIZEABLE = 'pie_customizeable';
const META_PIE_TEMPLATE_ID = 'template_id';
const META_PIE_VARIANT_CODE = 'variant_code';
const META_PIE_COLOR_CODE = 'pie_color_code';
const META_PIE_BACKGROUND_ID = 'pie_background_id';

const META_ADD_TO_CART_LABEL = 'custom_variation_add_to_cart_label';
const META_PRICE_PER_PAGE = 'price_per_page';
const META_BASE_PAGE_COUNT = 'base_page_count';
const META_CART_PRICE = 'cart_price';
const META_CART_UNITS = 'cart_units';
const META_UNIT_CODE = 'unit_code';
const META_CALL_TO_ORDER = 'call_to_order';

const META_PDF_REQUIRED = 'pdf_upload_required';
const META_PDF_WIDTH_MM = 'pdf_width_mm';
const META_PDF_HEIGHT_MM = 'pdf_height_mm';
const META_PDF_MIN_PAGES = 'pdf_min_pages';
const META_PDF_MAX_PAGES = 'pdf_max_pages';

class PWP_F2D_Product
{
    private \WC_Product $product;


    public function __construct(int $productId)
    {
        $this->product = wc_get_product($productId);
    }

    public function get_product(): \WC_Product
    {
        return $this->product;
    }

    public function get_product_meta(string $metaKey, bool $single = true): mixed
    {
        return $this->product->get_meta($metaKey, $single);
    }

    public function to_array()
    {
    }
}