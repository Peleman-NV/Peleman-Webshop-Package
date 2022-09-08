<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\handlers\PWP_Product_Attribute_Handler;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response_Component;
use WC_Product_Simple;

class PWP_Create_Simple_Product_Command implements PWP_I_Command
{
    private array $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function do_action(): PWP_I_Response_Component
    {
        $data = $this->data;
        $id = -1;
        try {
            $product = new WC_Product_Simple();

            $product->set_name($data['name']);
            $product->set_Reviews_allowed(isset($data['reviews_allowed']));
            $product->set_SKU($data['SKU']);
            $product->set_status($data['status']);
            $product->set_catalog_visibility($data['visibility'] ?: 'hidden');
            $product->set_regular_price($data['regular_price'] ?: 0.00);
            $product->set_sale_price($data['sale_price'] ?: 0.00);

            $product->set_upsell_ids($this->get_ids_from_skus($data['upsell_SKUs']));
            
            $product->set_cross_sell_ids($this->get_ids_from_skus($data['cross_sell_SKUs']));
            $product->set_tag_ids($this->get_tag_ids($data['tags']));
            
            $product->set_category_ids($this->get_category_ids($data['categories']));
            $product->set_attributes($this->get_attributes($data['attributes']));
            $product->set_default_attributes($this->get_default_attributes($data['defaultAttributes']));
            
            $product->set_image_id($data['main_image_id']);
            $product->set_gallery_image_ids($this->get_images($data['images']));

            foreach ($data['metadata'] as $key => $data) {
                $product->set_meta_data(array($key => $data));
            }
            //todo: define product
            // $id = $product->save();

            if ($id > 0) {
                return new PWP_Success_Notice(
                    'new simple product created',
                    "new simple product successfully created!"
                );
            }

            return new PWP_Error_Notice(
                'simple product not created',
                'simple product creation failed upon attempt to save to database'
            );
        } catch (\Exception $e) {
        }
    }

    public function undo_action(): PWP_I_Response_Component
    {
        return new PWP_Error_Notice(
            "method not implemented",
            "method " . __METHOD__ . " not implemented. Undo actions on database entries are not doable."
        );
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

    private function get_tag_ids(?array $slugs): array
    {
        if (is_null($slugs)) return array();

        $tagIds = array();
        $handler = new PWP_Term_SVC('product_cat', 'tax_product_cat', "product category");
        foreach ($slugs as $slug) {
            $result =  $handler->get_item_by_slug($slug);
            if (is_null($result)) {
                throw new PWP_Not_Found_Exception("tag with slug {$slug} not found in system");
            }
            $tagIds[] = $result->term_id;
        }

        return $tagIds;
    }

    private function get_attributes(?array $slugs): array
    {
        if (is_null($slugs)) return [];

        $handler = new PWP_Product_Attribute_Handler($this->logger);
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

    private function get_images(?array $ids): array
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    private function get_category_ids(?array $slugs): array
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    private function get_default_attributes(?array $slugs): array
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }
}
