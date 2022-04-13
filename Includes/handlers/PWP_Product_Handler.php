<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\API\endpoints\PWP_Attributes_Endpoint;
use stdClass;
use WC_Product;

use function PHPUnit\Framework\isNull;

class PWP_Product_Handler implements PWP_IHandler
{
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
        return wc_get_products($args);
    }

    public function create_item(array $data, array $args = []): object
    {
        $data['reviews_allowed'] = 0;
        $sku = $data['sku'];

        $id = (wc_get_product_id_by_sku($sku));
        $isNewProduct = empty($id);

        if (empty($data['lang'])) {
            if ($isNewProduct) {
                throw new \Exception("parent product not found (you are trying to upload a translated product, but its default language counterpart cannot be found!)s", 400);
            }

            $childId = apply_filters('wmpl_object_id', $id, 'post', false, $data['lang']);
            $isNewProduct = empty($childId);
            if ($isNewProduct) $id = $childId;
            $data['translation_of'] = $id;
        }

        $data['categories'] = $this->get_category_ids_from_slugs($data['categories']);
        $data['tags'] = $this->get_tag_ids_from_slugs($data['tags']);

        //get attributes and set first options to default

        $data['default_attributes'] = array();
        if (!empty($data['attributes'])) {
            foreach ($data['attributes'] as $key => $attribute) {
                $attributeKey = $this->get_attribute_id_by_slug($attribute['slug']);

                if (is_null($attributeKey)) {
                    continue;
                }

                $attribute['id'] = $attributeKey;

                if ($attribute['default'] !== false) {
                    $data['default_attributes'][$key]['id'] = $attribute['id'];
                    if (!empty($attribute->default)) {
                        $data['default_attributes'][$key]['option'] = $attribute['default'];
                        continue;
                    }
                    $data['default_attributes'][$key]['option'] = $attribute['options'][0];
                }
            }
        }

        //get images
        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
            // $imageId = $this->getImageIdByName($image['name'])};
        }

        //handle up- & cross-sell products
        if (!is_null($data['upsell_skus'])) {
            $data['upsell_ids'] = $this->get_product_ids_for_skus($data['upsell_skus']);
        }

        if (!is_null($data['upsell_skus'])) {
            $data['cross_sell_ids'] = $this->get_product_ids_for_skus($data['upsell_skus']);
        }

        //handle videos

        //create new item
        return new stdClass();
    }



    public function update_item(int $id, array $args = []): object
    {
        return new stdClass;
    }

    public function delete_item(int $id, array $args = []): bool
    {
        $forceDelete = (bool)$args['force'];

        $product = $this->get_item($id, $args);
        if (!$product instanceof WC_Product) throw new \Exception("value retrieved by database not of proper type!", 404);

        $childIds = $product->get_children();
        foreach ($childIds as $childId) {
            $this->get_item($childId)->delete($forceDelete);
        }
        return $product->delete($forceDelete);
    }

    #region utility methods
    /**
     * Undocumented function
     *
     * @param array $skus
     * @return int[]
     */
    private function get_product_ids_for_skus(array $skus): array
    {
        $ids = array();
        foreach ($skus as $sku) {
            $id = wc_get_product_id_by_sku($sku);
            if (empty($id)) {
                throw new \Exception("invalid product SKU entered!");
            }
            $ids[] = (int)$id;
        }

        return $ids;
    }

    private function get_category_ids_from_slugs(array $categories): array
    {
        return $this->get_term_ids_from_slugs($categories, new PWP_Category_Handler());
    }

    private function get_tag_ids_from_slugs(array $tags): array
    {
        return $this->get_term_ids_from_slugs($tags, new PWP_Tag_Handler());
    }

    private function get_term_ids_from_slugs(array $terms, PWP_Term_Handler $handler): array
    {
        foreach ($terms as $term) {
            if (!is_int($term['slug'])) {
                $term['id'] = $handler->get_item_by_slug($term['slug'])->term_id;
                //TODO: handle term id not found or invalid
            }
        }
        return $terms;
    }

    private function get_attribute_id_by_slug(string $slug): ?array
    {
        $handler = new PWP_Product_Attribute_Handler();
        $attribute = $handler->get_attribute_by_slug($slug);
        if (is_null($attribute)) {
            return null;
        }
        return $attribute['id'];
    }
    #endregion
}
