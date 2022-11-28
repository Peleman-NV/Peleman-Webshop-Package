<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\products;

use PWP\includes\utilities\schemas\Schema;

class Product_Schema
{
    private array $schema;
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
                'product_type' => array(
                    'type' => 'string',
                    'context' => array('view', 'edit'),
                    'enum' => array('simple', 'variable', 'variant', null),
                    'required' => true,
                ),
                'lang' => array(
                    'type' => 'string'
                ),
                'status' => array(
                    'type' => 'string',
                    'enum' => array(
                        'draft',
                        'pending',
                        'private',
                        'publish',
                    ),
                ),
                'featured' => array(
                    'type' => 'boolean'
                ),
                'catalog_visibility' => array(
                    'type' => 'string',
                    'enum' => array(
                        'visible',
                        'catalog',
                        'search',
                        'hidden',
                    ),
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
                    'type' => 'string',
                    'enum' => array(
                        'none',
                        'taxable',
                        'shipping',
                    ),
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
                /** PRODUCT DIMENSIONS OBJECT */
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
                    'required' => array(
                        'length',
                        'height',
                        'width'
                    )
                ),
                'upsell_skus' => array(
                    'type' => 'array',
                    'items' => array(
                        'type' => 'string'
                    ),
                    'additionalItems' => true,
                    'uniqueItems' => true
                ),
                'cross_sell_skus' => array(
                    'type' => 'array',
                    'additionalItems' => true,
                    'uniqueItems' => true,
                    'items' => array(
                        'type' => 'string'
                    )
                ),
                'parent_sku' => array(
                    'type' => 'string'
                ),
                'purchase_note' => array(
                    'type' => 'string'
                ),
                'categories' => array(
                    'type' => 'array',
                    'additionalItems' => true,
                    'uniqueItems' => true,
                    'items' => array(
                        'type' => 'string'
                    )

                ),
                'tags' => array(
                    'type' => 'array',
                    'items' => array(
                        'additionalItems' => true,
                        'uniqueItems' => true,
                        'type' => 'string'
                    ),
                ),
                /** IMAGES OBJECT */
                'images' => array(
                    'type' => 'array',
                    'items' => array(
                        'type' => 'string',
                    ),
                    'additionalItems' => true
                ),
                /** ATTRIBUTES OBJECT */
                'attributes' => array(
                    'type' => 'array',
                    'items' => array(
                        'uniqueItems' => true,
                        'additionalItems' => true,
                        'type' => 'object',
                        'properties' => array(
                            'name' => array(
                                'type' => 'string',
                            ),
                            'terms' => array(
                                'type' => 'array',
                                'items' => array(
                                    'type' => 'string',
                                )
                            ),
                            'is_visible' => array(
                                'type' => 'bool',
                            ),
                            'for_variation' => array(
                                'type' => 'bool'
                            ),
                        ),
                    ),
                ),
                'menu_order' => array(
                    'type' => 'int'
                ),
                'editor_id' => array(
                    'type' => 'string',
                    'enum' => array('PIE', 'IMAXEL', 'NONE', '', null),
                    'context' => array('view', 'edit'),
                ),
                //** PIE SETTINGS OBJECT */
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
                    'required' => array(
                        'template_id'
                    ),
                ),
                /** IMAXEL settings object */
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
                    'required' => array(
                        'template_id'
                    ),
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
                /** PDF UPLOAD SETTINGS OBJECT */
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
                    'required' => array(
                        'requires_upload',
                        'page_width',
                        'page_height'
                    )
                ),
                /** META DATA CONTAINER OBJECT */
                'meta_data' => array(
                    'type' => 'array',
                    'items' => array(
                        /** META DATA OBJECT */
                        'type' => 'object',
                        'properties' => array(
                            'key' => array(
                                'type' => 'string',
                                'required' => true,
                            ),
                            'value' => array(
                                'type' => array('number', 'integer', 'boolean', 'string', 'null'),
                                'required' => true,
                            )
                        ),
                        'required' => array(
                            'key',
                            'value'
                        )
                    ),
                    'additionalItems' => true
                ),
            ),
            'required' => array(
                'name',
                'sku',
                'f2d_sku',
                'f2d_article_code',
                'regular_price'
            ),
        );
    }

    public function get_schema(): array
    {
        return $this->schema;
    }
}
