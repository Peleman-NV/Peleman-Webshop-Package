<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\editor\PWP_Product_IMAXEL_Data;
use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\editor\PWP_Product_PIE_Data;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;
use WC_Product_Simple;

class PWP_Create_Simple_Product_Command extends PWP_Create_Product_Command
{
    public function do_action(): PWP_I_Response
    {
        if (isset($this->data['lang']) && 'en' !== $this->data['lang']) {
            $lang = $this->data['lang'];
            $sku = $this->data['sku'];
            $parent = wc_get_product(wc_get_product_id_by_sku($this->data['sku']));
            //unset SKU to prevent duplicate SKU errors.
            unset($this->data['sku']);
            if (empty($parent) || !$parent) {
                return new PWP_Error_Response(
                    'original translation not found.',
                    400,
                );
            }
        }

        //check for duplicate SKU
        if (wc_get_product_id_by_sku($this->data['sku'])) {
            return new PWP_Error_Response(
                'product with this SKU already exists',
                400,
                array('sku' => $this->data['sku'])
            );
        }
        $product = new WC_Product_Simple();
        $this->set_product_params_from_data($product);
        $productId = $product->save();

        if (0 >= $productId)
            return new PWP_Response("something went wrong", false, 404);

        /** start product meta data flow */
        $productMeta = new PWP_Product_Meta_Data($product);

        $productMeta->set_custom_add_to_cart_label($this->data['add_to_cart_label'] ?: '');

        switch ($this->data['editor_id']) {
            case 'PIE':
                $productMeta->set_editor(PWP_Product_PIE_Data::MY_EDITOR);
                break;
            case 'IMAXEL':
                $productMeta->set_editor(PWP_Product_IMAXEL_Data::MY_EDITOR);
                break;
            default:
                $productMeta->set_editor('');
                break;
        }

        /** SET PIE, IMAXEL, and PDF UPLOAD META SETTIGNS */
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

        // $this->configure_translation($product, $parent);
        return new PWP_Response("Product successfully created", true, 200, $product->get_data());
    }
}
