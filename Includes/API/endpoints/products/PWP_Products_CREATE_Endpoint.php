<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\products;

use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use PWP\includes\editor\PWP_Product_IMAXEL_Data;
use PWP\includes\editor\PWP_Product_Meta;
use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\editor\PWP_Product_PIE_Data;
use WC_Product;
use WC_Product_Simple;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Products_CREATE_Endpoint extends PWP_Abstract_CREATE_Endpoint
{
    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        switch ($request['type']) {
            case 'variable':
                break;
            case 'variant':
                break;
            default:
            case 'simple':
                $response = $this->create_new_simple_product($request);
                break;
        }
        return new WP_REST_Response('testing endpoint for product creation', 200);
    }

    private function create_new_simple_product(WP_REST_Request $request): array
    {
        $isTranslation = isset($request['lang']) && 'en' !== $request['lang'];
        if ($isTranslation) {
            $lang = $request['lang'];
            $parentId = wc_get_product_id_by_sku($request['sku']);
            unset($request['sku']);
            if (empty($parentId)) {
                throw new \Exception("parent product not found (default language counterpart)");
            }
        }
        /** set REQUIRED parameters on new product */
        $product = new WC_Product_Simple();
        $product->set_name($request['name']);
        $product->set_reviews_allowed(false);
        $product->set_SKU($request['sku']);
        $product->set_regular_price((string)$request['regular_price']);

        /** set OPTIONAL parameters on new products */
        $product->set_status($request['status'] ?: 'publish');
        $product->set_featured($request['featured'] ?: false);
        $product->set_catalog_visibility($request['catalog_visibility'] ?: "hidden");
        $product->set_description($request['description'] ?: '');
        $product->set_short_description($request['short_description'] ?: '');

        $product->set_tax_status($request['tax_status'] ?: 'taxable');
        $product->set_tax_class($request['tax_class'] ?: '');
        $product->set_sold_individually($request['sold_individually'] ?: false);

        $product->set_weight($request['weight'] ?: 00.00);
        $product->set_length($request['dimensions']['length'] ?: 00.00);
        $product->set_height($request['dimensions']['height'] ?: 00.00);
        $product->set_width($request['dimensions']['width'] ?: 00.00);

        $product->set_purchase_note($request['purchase_note'] ?: '');
        $product->set_menu_order($request['menu_order'] ?: -1);

        /** retrieve custom upload values and find relevant ids */
        $product->set_upsell_ids($this->get_product_ids_by_skus($request['upsell_skus']));
        $product->set_cross_sell_ids($this->get_product_ids_by_skus($request['cross_sell_skus']));
        $product->set_category_ids($this->get_tag_ids_by_slugs($request['tags']));
        $product->set_tag_ids($this->get_category_ids_by_slugs($request['categories']));

        $productId = $product->save();

        /** start product meta data flow */
        $productMeta = new PWP_Product_Meta_Data($product);

        $productMeta->set_custom_add_to_cart_label($request['add_to_cart_label'] ?: '');

        switch ($request['editor_id']) {
            case 'pie':
            case 'PIE':
                $productMeta->set_editor(PWP_Product_PIE_Data::MY_EDITOR);
                break;
            case 'imaxel':
            case 'IMAXEL':
                $productMeta->set_editor(PWP_Product_IMAXEL_Data::MY_EDITOR);
                break;
            case 'none':
            case 'NONE':
            default:
                break;
        }

        if (isset($request['pie_settings'])) {
            $this->set_product_pie_data($productMeta, $request['pie_settings']);
        }

        if (isset($request['imaxel_settings'])) {
            $this->set_product_imaxel_data($productMeta, $request['imaxel_settings']);
        }

        if (isset($request['pdf_upload'])) {
            $this->set_product_pdf_data($productMeta, $request['pdf_upload']);
        }
        $productMeta->update_meta_data();
        $productMeta->save();


        foreach ($request['meta_data'] as $meta) {
            $product->set_meta_data([$meta['key'] => $meta['value']]);
        }
        $product->save_meta_data();

        return $product->get_data();
    }

    private function get_product_ids_by_skus(array $skus): array
    {
        $ids = [];
        foreach ($skus as $sku) {
            $id = wc_get_product_id_by_sku($sku);
            if (empty($id)) continue;
            $ids[] = $id;
        }

        return $ids;
    }

    private function get_tag_ids_by_slugs(array $slugs): array
    {
        $ids = [];
        foreach ($slugs as $slug) {
            $tag = get_term_by('slug', $slug, 'post_tag');
            if (!$tag) continue;
            $ids[] = $tag->term_id;
        }

        return $ids;
    }

    private function get_category_ids_by_slugs(array $slugs): array
    {
        $ids = [];
        foreach ($slugs as $slug) {
            $category = get_category_by_slug($slug);
            if (!$category) continue;
            $ids[] = $category->term_id;
        }

        return $ids;
    }

    private function set_product_pie_data(PWP_Product_Meta_Data $meta, array $request): void
    {
        $data = $meta->pie_data();
        $data
            ->set_template_id($request['template_id'])
            ->set_design_id($request['design_id'] ?: '')
            ->set_min_images($request['min_images'] ?: 0)
            ->set_max_images($request['max_images'] ?: 0)
            ->set_color_code($request['color_code'] ?: '')
            ->set_background_id($request['background_id'] ?: '')
            ->set_format_id($request['format_id'] ?: '')
            ->set_num_pages($request['pages_to_fill'] ?: 0)
            ->set_editor_instructions($request['editor_instructions'] ?: []);
    }

    private function set_product_imaxel_data(PWP_Product_Meta_Data $meta, array $request): void
    {
        $data = $meta->imaxel_data();
        $data
            ->set_template_id($request['template_id'])
            ->set_variant_id($request['variant_code'] ?: '');
    }

    private function set_product_pdf_data(PWP_Product_Meta_Data $meta, array $request): void
    {
        $meta->set_uses_pdf_content($request['requires_upload'] ?: false)
            ->set_pdf_min_pages($request['min_pages'] ?: 0)
            ->set_pdf_max_pages($request['max_pages'] ?: 0)
            ->set_pdf_width($request['page_width'] ?: 0)
            ->set_pdf_height($request['pdf_height'] ?: 0);
    }
}
