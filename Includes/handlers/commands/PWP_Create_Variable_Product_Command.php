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
        $product->set_attributes($this->wc_prepare_product_attributes($this->data['attributes']));
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

    public function wc_prepare_product_attributes(array $attributes): array
    {

        $data = array();
        $position = 0;

        foreach ($attributes as $values) {
            // $name => $values)
            $name = $values['name'];
            $taxonomy = 'pa_' . $name;
            if (!taxonomy_exists($taxonomy)) {
                error_log("taxonomy already exists: {$taxonomy}");
                continue;
            }

            // Get an instance of the WC_Product_Attribute Object
            $attribute = new \WC_Product_Attribute();

            $term_ids = array();
            $terms = $values['terms'];

            // Loop through the term names
            foreach ($values['terms'] as $term_name) {
                if (term_exists($term_name, $taxonomy))
                    // Get and set the term ID in the array from the term name
                    $term_ids[] = get_term_by('name', $term_name, $taxonomy)->term_id;
                else
                    continue;
            }

            error_log(print_r($term_ids, true));
            $taxonomy_id = wc_attribute_taxonomy_id_by_name($taxonomy); // Get taxonomy ID
            error_log('taxonomy id: ' . $taxonomy_id);

            $attribute->set_id($taxonomy_id);
            $attribute->set_name($name);
            // $attribute->set_options($term_ids);
            $attribute->set_options($terms);
            $attribute->set_position($position);
            $attribute->set_visible($values['is_visible'] ?: false);
            $attribute->set_variation($values['for_variation'] ?: false);

            $data[$taxonomy] = $attribute; // Set in an array

            $position++; // Increase position
        }
        return $data;
    }
}
