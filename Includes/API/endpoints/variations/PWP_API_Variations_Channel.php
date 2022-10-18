<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\variations;

use PWP\includes\API\PWP_Abstract_API_Channel;
use PWP\includes\API\endpoints\PWP_I_Endpoint;
use PWP\includes\API\endpoints\PWP_Test_Endpoint;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\authentication\PWP_I_Api_Authenticator;

defined('ABSPATH') || die;

class PWP_API_Variantions_Channel extends PWP_Abstract_API_Channel
{
    /**
     * @var PWP_I_Endpoint[]
     */
    protected array $endpoints;
    protected PWP_I_Api_Authenticator $authenticator;

    public function __construct(string $namespace, string $rest_base = '', PWP_I_Api_Authenticator $authenticator = null)
    {
        parent::__construct($namespace, 'product variations', $rest_base . "/variations", $authenticator ?: new PWP_Authenticator());

        /* REGISTER ENDPOINTS HERE */

        //Testing Endpoint; to be removed when no longer relevant
        // $this->register_endpoint(new PWP_Test_Endpoint($this->get_definition()->get_namespace(), $this->authenticator));

        // $this->register_endpoint(new PWP_Variations_BATCH_Endpoint($this->definition, $this->authenticator));
        $this->register_endpoint(new PWP_Variations_CREATE_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new PWP_Variations_READ_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new PWP_Variations_FIND_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new PWP_Variations_UPDATE_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new PWP_Variations_DELETE_Endpoint($this->definition, $this->authenticator));

        // $this->register_endpoint(new PWP_Variations_UNPARENT_Endpoint($this->definition, $this->authenticator));
    }
}
