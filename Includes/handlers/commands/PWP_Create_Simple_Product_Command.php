<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\handlers\PWP_Product_Attribute_Handler;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response;
use WC_Product_Simple;

class PWP_Create_Simple_Product_Command implements PWP_I_Command
{
    private array $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function do_action(): PWP_I_Response
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

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Error_Notice(
            "method not implemented",
            "method " . __METHOD__ . " not implemented. Undo actions on database entries are not doable."
        );
    }
}
