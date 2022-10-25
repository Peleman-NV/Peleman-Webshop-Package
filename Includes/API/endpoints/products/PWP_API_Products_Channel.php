<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\products;

use PWP\includes\API\PWP_Abstract_API_Channel;
use PWP\includes\API\endpoints\PWP_I_Endpoint;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\authentication\PWP_I_Api_Authenticator;

defined('ABSPATH') || die;

class PWP_API_Products_Channel extends PWP_Abstract_API_Channel
{
    /**
     * @var PWP_I_Endpoint[]
     */
    protected array $endpoints;
    protected PWP_I_Api_Authenticator $authenticator;

    public function __construct(string $namespace, string $rest_base = '', PWP_I_Api_Authenticator $authenticator = null)
    {
        parent::__construct($namespace, 'products', $rest_base . "/products", $authenticator ?: new PWP_Authenticator());

        /* REGISTER ENDPOINTS HERE */

        //Testing Endpoint; to be removed when no longer relevant
        // $this->register_endpoint(new PWP_Test_Endpoint($this->get_definition()->get_namespace(), $this->authenticator));

        // $this->register_endpoint(new PWP_Products_BATCH_Endpoint($this->definition, $this->authenticator));
        $this->register_endpoint(new PWP_Products_CREATE_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new PWP_Products_READ_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new PWP_Products_FIND_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new PWP_Products_UPDATE_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new PWP_Products_DELETE_Endpoint($this->definition, $this->authenticator));
    }
}