<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;
use WC_Product_Variable;

class PWP_Create_Variable_Product_Command extends PWP_Create_Product_Command
{
    public function do_action(): PWP_I_Response
    {
        //check for duplicate SKU
        if (wc_get_product_id_by_sku($this->data['sku'])) {
            return new PWP_Error_Response(
                'product with this SKU already exists',
                400,
                array('sku' => $this->data['sku'])
            );
        }
        $product = new WC_Product_Variable();
        $this->set_product_params_from_data($product);
        $product->set_default_attributes(array(
            'name' => 'edition',
            'option' => 'firsted'
        ));
        $productId = $product->save();

        if (0 >= $productId) {
            return new PWP_Error_Response("Something went wrong trying to save a new product, 404");
        }

        /** start product meta data flow */
        $productMeta = new PWP_Product_Meta_Data($product);

        $productMeta->set_custom_add_to_cart_label($this->data['add_to_cart_label'] ?: '');

        if (isset($this->data['editor_id'])) {
            $this->set_editor_id($productMeta, $this->data['editor_id']);
        }
        if (isset($this->data['pie_settings'])) {
            $this->set_product_pie_data($productMeta, $this->data['pie_settings']);
        }

        if (isset($this->data['imaxel_settings'])) {
            $this->set_product_imaxel_data($productMeta, $this->data['imaxel_settings']);
        }

        if (isset($this->data['pdf_upload'])) {
            $this->set_product_pdf_data($productMeta, $this->data['pdf_upload']);
        }

        foreach ($this->data['meta_data'] as $meta) {
            $product->set_meta_data([$meta['key'] => $meta['value']]);
        }
        /** UPDATE & SAVE META DATA */
        $productMeta->update_meta_data();
        $productMeta->save_meta_data();


        return new PWP_Response(
            'new Variable product created',
            true,
            200,
            $product->get_data()
        );
    }
}
