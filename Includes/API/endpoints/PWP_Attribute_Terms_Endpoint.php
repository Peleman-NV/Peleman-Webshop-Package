<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_IApiAuthenticator;

class PWP_Attribute_Terms_Endpoint extends PWP_EndpointController
{
    public function __construct()
    {
        parent::__construct(
            "/attributes/(?P<attributeId>\d+)/terms',",
            'attribute term'
        );
    }

}