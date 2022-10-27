<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\products;

use PWP\includes\API\Abstract_API_Channel;
use PWP\includes\API\endpoints\I_Endpoint;
use PWP\includes\authentication\Authenticator;
use PWP\includes\authentication\I_Api_Authenticator;

defined('ABSPATH') || die;

class API_Products_Channel extends Abstract_API_Channel
{
    /**
     * @var I_Endpoint[]
     */
    protected array $endpoints;
    protected I_Api_Authenticator $authenticator;

    public function __construct(string $namespace, string $rest_base = '', I_Api_Authenticator $authenticator = null)
    {
        parent::__construct($namespace, 'products', $rest_base . "/products", $authenticator ?: new Authenticator());

        /* REGISTER ENDPOINTS HERE */

        //Testing Endpoint; to be removed when no longer relevant
        // $this->register_endpoint(new Test_Endpoint($this->get_definition()->get_namespace(), $this->authenticator));

        // $this->register_endpoint(new Products_BATCH_Endpoint($this->definition, $this->authenticator));
        $this->register_endpoint(new Products_CREATE_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Products_READ_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Products_FIND_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Products_UPDATE_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Products_DELETE_Endpoint($this->definition, $this->authenticator));
    }
}