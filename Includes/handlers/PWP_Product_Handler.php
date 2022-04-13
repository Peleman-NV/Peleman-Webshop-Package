<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

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

            //get category ids
            $categoryHandler = new PWP_Category_Handler();
            if (!empty($data['categories'])) {
                foreach ($data['category'] as $category) {
                    if (!is_int($category['slug'])) {
                        $category['id'] = $categoryHandler->get_item_by_slug($category['slug']);
                    }
                }
            }

            //get tag ids

            //get attributes and set first options to default

            //get images

            //handle up- & cross-sell products
            if (!is_null($data['upsell_skus'])) {
                $data['upsell_ids'] = $this->get_product_ids_for_skus($data['upsell_skus']);
            }

            if (!is_null($data['upsell_skus'])) {
                $data['cross_sell_ids'] = $this->get_product_ids_for_skus($data['upsell_skus']);
            }

            //handle videos

            //create new item

        }

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


    #endregion
}
