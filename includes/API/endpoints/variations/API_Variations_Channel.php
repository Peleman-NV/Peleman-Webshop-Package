<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\variations;

use PWP\includes\API\Abstract_API_Channel;
use PWP\includes\API\endpoints\I_Endpoint;
use PWP\includes\API\endpoints\Test_Endpoint;
use PWP\includes\authentication\Authenticator;
use PWP\includes\authentication\I_Api_Authenticator;

defined('ABSPATH') || die;

class API_Variantions_Channel extends Abstract_API_Channel
{
    /**
     * @var I_Endpoint[]
     */
    protected array $endpoints;
    protected I_Api_Authenticator $authenticator;

    public function __construct(string $namespace, string $rest_base = '', I_Api_Authenticator $authenticator = null)
    {
        parent::__construct($namespace, 'product variations', $rest_base . "/variations", $authenticator ?: new Authenticator());

        /* REGISTER ENDPOINTS HERE */

        //Testing Endpoint; to be removed when no longer relevant
        // $this->register_endpoint(new Test_Endpoint($this->get_definition()->get_namespace(), $this->authenticator));

        // $this->register_endpoint(new Variations_BATCH_Endpoint($this->definition, $this->authenticator));
        $this->register_endpoint(new Variations_CREATE_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Variations_READ_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Variations_FIND_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Variations_UPDATE_Endpoint($this->definition, $this->authenticator));
        // $this->register_endpoint(new Variations_DELETE_Endpoint($this->definition, $this->authenticator));

        // $this->register_endpoint(new Variations_UNPARENT_Endpoint($this->definition, $this->authenticator));
    }
}
