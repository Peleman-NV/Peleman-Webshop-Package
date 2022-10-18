<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\attributes;

use PWP\includes\API\endpoints\PWP_Abstract_READ_Endpoint;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use WP_REST_Response;

class PWP_Attributes_READ_Endpoint extends PWP_Abstract_READ_Endpoint
{
    public function __construct(PWP_Channel_Definition $channel, PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $channel->get_namespace(),
            $channel->get_route(),
            $channel->get_title(),
            $this->authenticator = $authenticator
        );
    }

    public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        $taxonomies = $this->get_attribute_taxonomy_array();
        return new WP_REST_Response(array(
            'status' => 'success',
            'data' => $taxonomies,
        ), 200);
    }

    /**
     * Undocumented function
     *
     * @return array array of attribute taxonomies by slug/attribute_name
     */
    private function get_attribute_taxonomy_array(): array
    {
        $currentAttributes = wc_get_attribute_taxonomies();

        $currentAttributesArray = array();
        foreach ($currentAttributes as $attribute) {
            $currentAttributesArray[$attribute->attribute_name] = array(
                'id' => (int)$attribute->attribute_id,
                'slug' => $attribute->attribute_name,
                'name' => $attribute->attribute_label,
                'type' => $attribute->attribute_type,
                'order_by' => $attribute->attribute_orderby,
                'public' => $attribute->attribute_public,
            );
        }
        return $currentAttributesArray;
    }
}
