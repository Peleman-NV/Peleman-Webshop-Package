<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\products;

use PWP\includes\utilities\schemas\PWP_Schema;

class PWP_Product_Schema extends PWP_Schema
{
    public function __construct()
    {
        $this->schema = array(
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'Product',
            'description' => 'Product',
            'type' => 'object',
            'properties' => array(
                'name' => array(
                    'type' => 'string',
                    'description' => __('Name of the product being created, will be displayed in the store page.'),
                    'context' => array('view', 'edit'),
                    'required' => true,
                ),
                'reviews_allowed' => array(
                    'type' => 'bool',
                ),
                'sku' => array(
                    'type' => 'string',
                    'description' => __('Product specific SKU for use within woocommerce. Must be unique within woocommerce.'),
                    'context' => array('view', 'edit'),
                    'required' => true,
                ),
                'f2d_sku' => array(
                    'type' => 'string',
                    'required' => true,
                ),
                'f2d_article_code' => array(
                    'type' => 'string',
                    'required' => 'true',
                ),
                'add_to_cart_label' => array(
                    'type' => 'string'
                ),
                'call_to_order' => array(
                    'type' => 'boolean'
                ),
                'type' => array(
                    'type' => 'string',
                    'context' => array('view', 'edit'),
                    'enum' => array('simple', 'variable', 'variant', null),
                    'required' => true,
                ),
                'lang' => array(
                    'type' => 'string'
                ),
                'status' => array(
                    'type' => 'string'
                ),
                'featured' => array(
                    'type' => 'boolean'
                ),
                'catalog_visibility' => array(
                    'type' => 'string'
                ),
                'description' => array(
                    'type' => 'string'
                ),
                'short_description' => array(
                    'type' => 'string'
                ),
                'regular_price' => array(
                    'type' => 'number',
                    'required' => true,
                    'minimum' => 0,
                    'exclusiveMinimum' => true
                ),
                'tax_status' => array(
                    'type' => 'string'
                ),
                'tax_class' => array(
                    'type' => 'string'
                ),
                'sold_individually' => array(
                    'type' => 'boolean'
                ),
                'weight' => array(
                    'type' => 'number'
                ),
                'dimensions' => array(
                    'type' => 'object',
                    'properties' => array(
                        'length' => array(
                            'type' => 'number'
                        ),
                        'height' => array(
                            'type' => 'number'
                        ),
                        'width' => array(
                            'type' => 'number'
                        )
                    ),
                    'required' => [
                        'length',
                        'height',
                        'width'
                    ]
                ),
                'upsell_skus' => array(
                    'type' => 'array',
                    'items' => [
                        array(
                            'type' => 'string'
                        )
                    ],
                    'additionalItems' => true,
                    'uniqueItems' => true
                ),
                'cross_sell_skus' => array(
                    'type' => 'array',
                    'items' => [
                        array(
                            'type' => 'string'
                        )
                    ],
                    'additionalItems' => true,
                    'uniqueItems' => true
                ),
                'parent_sku' => array(
                    'type' => 'string'
                ),
                'purchase_note' => array(
                    'type' => 'string'
                ),
                'categories' => array(
                    'type' => 'array',
                    'items' => [
                        array(
                            'type' => 'string'
                        )
                    ],
                    'additionalItems' => true,
                    'uniqueItems' => true
                ),
                'tags' => array(
                    'type' => 'array',
                    'items' => [
                        array(
                            'type' => 'string'
                        )
                    ],
                    'additionalItems' => true,
                    'uniqueItems' => true
                ),
                'images' => array(
                    'type' => 'array',
                    'items' => array(),
                    'additionalItems' => true
                ),
                'menu_order' => array(
                    'type' => 'int'
                ),
                'editor_id' => array(
                    'type' => 'string',
                    'enum' => array('PIE', 'IMAXEL', 'NONE', '', null),
                    'context' => array('view', 'edit'),
                ),
                'pie_settings' => array(
                    'type' => 'object',
                    'properties' => array(
                        'template_id' => array(
                            'type' => 'string'
                        ),
                        'design_id' => array(
                            'type' => 'string'
                        ),
                        'min_images' => array(
                            'type' => 'integer',
                            'minimum' => 0
                        ),
                        'max_images' => array(
                            'type' => 'integer',
                            'minimum' => 0
                        ),
                        'color_code' => array(
                            'type' => 'string'
                        ),
                        'background_id' => array(
                            'type' => 'string'
                        ),
                        'format_id' => array(
                            'type' => 'string'
                        ),
                        'pages_to_fill' => array(
                            'type' => 'integer',
                            'minimum' => 0
                        ),
                        'editor_instructions' => array(
                            'type' => 'array',
                            'context' => array('view', 'edit'),
                            'uniqueItems' => true,
                            'items' => array(
                                'type' => 'string',
                                'pattern' => '^[A-Z_]*$',
                            ),
                        ),
                    ),
                    'required' => [
                        'template_id'
                    ]
                ),
                'imaxel_settings' => array(
                    'type' => 'object',
                    'properties' => array(
                        'template_id' => array(
                            'type' => 'string'
                        ),
                        'variant_code' => array(
                            'type' => 'string'
                        )
                    ),
                    'required' => [
                        'template_id'
                    ]
                ),
                'price_per_page' => array(
                    'type' => 'number',
                    'minimum' => 0
                ),
                'base_page_count' => array(
                    'type' => 'integer',
                    'minimum' => 0
                ),
                'unit_price' => array(
                    'type' => 'number',
                    'minimum' => 0
                ),
                'unit_amount' => array(
                    'type' => 'integer'
                ),
                'unit_code' => array(
                    'type' => 'string'
                ),
                'pdf_upload' => array(
                    'type' => 'object',
                    'properties' => array(
                        'requires_upload' => array(
                            'type' => 'boolean'
                        ),
                        'min_pages' => array(
                            'type' => 'integer',
                            'minimum' => 0
                        ),
                        'max_pages' => array(
                            'type' => 'integer',
                            'minimum' => 0
                        ),
                        'page_width' => array(
                            'type' => 'integer'
                        ),
                        'page_height' => array(
                            'type' => 'integer'
                        )
                    ),
                    'required' => [
                        'requires_upload',
                        'min_pages',
                        'max_pages',
                        'page_width',
                        'page_height'
                    ]
                ),
                'meta_data' => array(
                    'type' => 'array',
                    'items' => [
                        array(
                            'type' => 'object',
                            'properties' => array(
                                'key' => array(
                                    'type' => 'string'
                                ),
                                'value' => array(
                                    'type' => ['number', 'integer', 'boolean', 'string', 'null']
                                )
                            ),
                            'required' => [
                                'key',
                                'value'
                            ]
                        )
                    ],
                    'additionalItems' => true
                )
            ),
            'required' => [
                'name',
                'sku',
                'f2d_sku',
                'f2d_article_code',
                'regular_price'
            ]
        );
    }
}
