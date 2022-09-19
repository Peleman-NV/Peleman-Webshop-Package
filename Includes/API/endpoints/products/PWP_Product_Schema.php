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
            'type' => 'object',
            'properties' => array(
                'type' => array(
                    'type' => 'string',
                    'context' => array('view', 'edit'),
                    'enum' => array('simple', 'variable', 'variant', null),
                    'required' => true,
                ),
                'name' => array(
                    'type' => 'string',
                    'context' => array('view', 'edit'),
                    'required' => true,
                ),
                'sku' => array(
                    'type' => 'string',
                    'context' => array('view', 'edit'),
                    'required' => true,
                ),
                'editor_id' => array(
                    'type' => 'string',
                    'enum' => array('PIE', 'IMAXEL', 'NONE', '', null),
                    'context' => array('view', 'edit'),
                ),
                'pie_settings' => array(
                    'type' => 'object',
                    'context' => array('view', 'edit'),
                    'properties' => array(
                        'template_id' => array(
                            'type' => 'string',
                            'context' => array('view', 'edit'),
                            'required' => true,
                        ),
                        'design_id' => array(
                            'type' => 'string',
                            'context' => array('view', 'edit')
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
                        'template_id',
                    ),
                ),
            ),
            'required' => array(
                'name',
                'sku',
                'type',
            )
        );
    }
}
