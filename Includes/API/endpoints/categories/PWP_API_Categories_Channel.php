<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\API\PWP_Abstract_API_Channel;
use PWP\includes\API\endpoints\PWP_I_Endpoint;
use PWP\includes\API\endpoints\PWP_Test_Endpoint;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\authentication\PWP_IApiAuthenticator;

defined('ABSPATH') || die;

class PWP_API_Categories_Channel extends PWP_Abstract_API_Channel
{

    /**
     * @var PWP_I_Endpoint[]
     */
    protected array $endpoints;
    protected PWP_IApiAuthenticator $authenticator;

    public function __construct(string $namespace, string $rest_base = '', PWP_IApiAuthenticator $authenticator = null)
    {
        parent::__construct($namespace, $rest_base . "/categories", $authenticator ?: new PWP_Authenticator());

        /* REGISTER ENDPOINTS HERE */

        //Testing Endpoint; to be removed when no longer relevant
        $this->add_endpoint(new PWP_Test_Endpoint($this->authenticator));

        $this->add_endpoint(new PWP_Categories_BATCH_Endpoint($this->rest_base, $this->authenticator));

        $this->add_endpoint(new PWP_Categories_CREATE_Endpoint($this->rest_base, $this->authenticator));

        $this->add_endpoint(new PWP_Categories_READ_Endpoint($this->rest_base, $this->authenticator));
        $this->add_endpoint(new PWP_Categories_FIND_Endpoint($this->rest_base, $this->authenticator));

        $this->add_endpoint(new PWP_Categories_UPDATE_Endpoint($this->rest_base, $this->authenticator));

        $this->add_endpoint(new PWP_Categories_DELETE_Endpoint($this->rest_base, $this->authenticator));
    }
}
