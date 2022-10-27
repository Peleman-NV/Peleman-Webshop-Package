<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\attributes;

use PWP\includes\API\endpoints\attributes\terms\Attribute_Terms_Create_Endpoint;
use PWP\includes\API\endpoints\attributes\terms\Attribute_Terms_READ_Endpoint;
use PWP\includes\API\Abstract_API_Channel;
use PWP\includes\API\endpoints\I_Endpoint;
use PWP\includes\authentication\Authenticator;
use PWP\includes\authentication\I_Api_Authenticator;

defined('ABSPATH') || die;

class API_Attributes_Channel extends Abstract_API_Channel
{
    /**
     * @var I_Endpoint[]
     */
    protected array $endpoints;
    protected I_Api_Authenticator $authenticator;

    public function __construct(string $namespace, string $rest_base = '', I_Api_Authenticator $authenticator = null)
    {
        parent::__construct($namespace, 'product attributes', $rest_base . "/attributes", $authenticator ?: new Authenticator());

        /* REGISTER ENDPOINTS HERE */

        //Testing Endpoint; to be removed when no longer relevant
        // $this->register_endpoint(new Test_Endpoint($this->get_definition()->get_namespace(), $this->authenticator));

        // $this->register_endpoint(new Attributes_BATCH_Endpoint($this->definition, $this->authenticator));
        $this->register_endpoint(new Attributes_CREATE_Endpoint($this->definition, $this->authenticator));
        $this->register_endpoint(new Attributes_READ_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Attributes_FIND_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Attributes_UPDATE_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Attributes_DELETE_Endpoint($this->definition, $this->authenticator));

        // $this->register_endpoint(new Attributes_UNPARENT_Endpoint($this->definition, $this->authenticator));

        $this->register_endpoint(new Attribute_Terms_READ_Endpoint(
            $namespace,
            $rest_base . "/attributes/(?P<slug>\w+)/",
            'attribute terms',
            $this->authenticator
        ));
        $this->register_endpoint(new Attribute_Terms_Create_Endpoint(
            $namespace,
            $rest_base . "/attributes/(?P<slug>\w+)/",
            'attribute terms',
            $this->authenticator
        ));
    }
}
