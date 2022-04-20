<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WC_Product;
use function PHPUnit\Framework\isNull;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

class PWP_Product_Handler implements PWP_IHandler
{

    public function create_item(string $identifier, array $args = []): object
    {
        $args = (object)$args;
        try {
            $product = new WC_Product();

            $product->set_name($args->name);
            $product->set_reviews_allowed(false);

            if (!empty($args->lang)) {
                $parentId = wc_get_product_id_by_sku($args->SKU);
                if (empty($parentId)) {
                    throw new \Exception("Parent product not found (default language counterpart not found in database)", 400);
                }
            }
            if (wc_get_product_id_by_sku($args->SKU) > 0) {
                throw new \Exception("product with this SKU already exists!", 400);
            }

            $product->set_SKU($args->SKU);
            $product->set_status($args->status);

            $product->set_catalog_visibility($args->visibility ?: 'hidden');

            if (!empty($args->parent_id)) {
                $product->set_parent_id($args->parent);
            }
            $product->set_price($this->price);
            $product->set_regular_price($args->regular_price);
            $product->set_sale_price($args->sale_price);

            $product->set_upsell_ids($this->get_ids_from_skus($args->upsell_SKUs));
            $product->set_cross_sell_ids($this->get_ids_from_skus($args->cross_sell_SKUs));

            $product->set_tag_ids($this->get_tag_ids($args->tags));
            $product->set_category_ids($this->get_category_ids($args->categories));
            $product->set_attributes($this->get_attributes($args->attributes));
            $product->set_default_attributes($this->get_default_attributes($args->default_attributes));

            $product->set_image_id($args->main_image_id);
            $product->set_gallery_image_ids($this->get_images($args->images));

            $product->set_meta_data(array('customizable' => $args->customizable ?: false));
            $product->set_meta_data(array('template-id' => $args->template_id));
            $product->set_meta_data(array('template-variant-id' => $args->template_variant_id));
            if (!empty($args->lang)) {
                $product->set_meta_data(array('lang' => $args->lang));
            }
        } catch (\Exception $exception) {
            throw $exception;
        }

        $id = $product->save();

        if ($id <= 0) {
            throw new \Exception("something went wrong when trying to save a new product!", 500);
        }

        return $product;
    }

    public function get_item(int $id, array $args = []): object
    {
        $response = wc_get_product($id);
        if (!$response || isNull($response)) {
            throw new \Exception("no product matching id {$id} found in database!", 404);
        }

        return $response;
    }

    public function get_items(array $args = []): array
    {
        $args['paginate'] = 'true';
        $args['return'] = 'objects';

        $results = wc_get_products($args);
        $results['products'] = $this->remap_results_array($results['products']);

        return $results;
    }

    public function update_item(int $id, array $args = []): object
    {
        throw new PWP_Not_Implemented_Exception();
    }

    public function delete_item(int $id, array $args = []): bool
    {
        $forceDelete = $args['force'] ? (bool)$args['force'] : false;

        $product = $this->get_item($id, $args);
        if (!$product instanceof WC_Product) throw new \Exception("value retrieved by database not of proper type!", 404);

        $childIds = $product->get_children();
        foreach ($childIds as $childId) {
            $this->get_item($childId)->delete($forceDelete);
        }
        return $product->delete($forceDelete);
    }

    private function get_ids_from_skus(?array $skus): array
    {
        if (is_null($skus)) return array();
        $ids = array();
        foreach ($skus as $sku) {
            $id = wc_get_product_id_by_sku($sku);
            if ($id <= 0) throw new \Exception("invalid product SKU entered: {$sku}");

            $ids[] = $id;
        }

        return $ids;
    }

    private function get_images(?array $ids): array
    {
        throw new PWP_Not_Implemented_Exception();
    }

    private function get_tag_ids(?array $slugs): array
    {
        if (is_null($slugs)) return array();

        $tagIds = array();
        $handler = new PWP_Tag_Handler();
        foreach ($slugs as $slug) {
            $result =  $handler->get_item_by_slug($slug);
            if (isNull($result)) {
                throw new PWP_Not_Found_Exception("tag with slug {$slug} not found in system");
            }
            $tagIds[] = $result->term_id;
        }

        return $tagIds;
    }

    private function get_category_ids(?array $slugs): array
    {
        throw new PWP_Not_Implemented_Exception();
    }

    private function get_attributes(?array $slugs): array
    {
        if (is_null($slugs)) return [];

        $handler = new PWP_Product_Attribute_Handler();
        $attributeIds = array();
        foreach ($slugs as $slug) {
            $attribute = $handler->get_attribute_by_slug($slug);
            if (is_null($attribute)) {
                continue;
            }
            $attributeIds[] = $attribute['id'];
        }

        return $attributeIds;
    }

    private function get_default_attributes(?array $slugs): array
    {
        throw new PWP_Not_Implemented_Exception();
    }

    private function remap_results_array(array $products): array
    {
        return array_map(
            function ($product) {
                if (!$product instanceof \WC_Product) {
                    return $product;
                }
                $data = $product->get_data();
                $data['variations'] = $product->get_children();
                return $data;
            },
            $products
        );
    }
}
