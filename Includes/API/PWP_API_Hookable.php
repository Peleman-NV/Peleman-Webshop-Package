<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\API\endpoints\PWP_I_Endpoint;

use PWP\includes\API\endpoints\PWP_Test_Endpoint;

use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\hookables\PWP_IHookableComponent;

use PWP\includes\authentication\PWP_IApiAuthenticator;

use PWP\includes\API\endpoints\categories\PWP_Categories_READ_Endpoint;
use PWP\includes\API\endpoints\categories\PWP_Categories_BATCH_Endpoint;
use PWP\includes\API\endpoints\categories\PWP_Categories_CREATE_Endpoint;
use PWP\includes\API\endpoints\categories\PWP_Categories_DELETE_Endpoint;
use PWP\includes\API\endpoints\categories\PWP_Categories_FIND_Endpoint;

defined('ABSPATH') || die;

/**
 * Hookable component of the Peleman Webshop Package plugin, responsible for the REST API component
 */
class PWP_API_Hookable implements PWP_IHookableComponent
{
    protected string $namespace;
    protected string $rest_base;

    /**
     * @var PWP_I_Endpoint[]
     */
    protected array $endpoints;
    protected PWP_IApiAuthenticator $authenticator;

    public function __construct(?string $namespace)
    {
        $this->namespace = $namespace ?: 'pwp/v1';
        $this->rest_base = '';
        $this->authenticator = new PWP_Authenticator();

        //Testing Endpoints; to be removed when no longer relevant
        $this->add_endpoint(new PWP_Test_Endpoint($this->authenticator));

        //TODO: look into why this endpoint specifically crashes the wp-admin panel
        // $this->add_endpoint(new PWP_Products_Endpoint($this->authenticator));
        // $this->add_endpoint(new PWP_Product_Variations_Endpoint($this->authenticator));

        // $this->add_endpoint(new PWP_Tags_Endpoint($this->authenticator));

        $this->add_endpoint(new PWP_Categories_CREATE_Endpoint($this->rest_base, $this->authenticator));
        $this->add_endpoint(new PWP_Categories_READ_Endpoint($this->rest_base, $this->authenticator));
        $this->add_endpoint(new PWP_Categories_FIND_Endpoint($this->rest_base, $this->authenticator));
        $this->add_endpoint(new PWP_Categories_BATCH_Endpoint($this->rest_base, $this->authenticator));
        $this->add_endpoint(new PWP_Categories_DELETE_Endpoint($this->rest_base, $this->authenticator));
        $this->add_endpoint(new PWP_Categories_DELETE_Endpoint($this->rest_base, $this->authenticator));

        // $this->add_endpoint(new PWP_Attributes_Endpoint($this->authenticator));
        // $this->add_endpoint(new PWP_Attribute_Terms_Endpoint($this->authenticator));

        // $this->add_endpoint(new PWP_Images_Endpoint($this->authenticator));
        // $this->add_endpoint(new PWP_Customers_Endpoint($this->authenticator));
        // $this->add_endpoint(new PWP_Orders_Endpoint($this->authenticator));

        // $this->add_endpoint(new PWP_Menus_Endpoint($this->authenticator));

        // $this->add_endpoint(new PWP_Languages_Endpoint($this->authenticator));
    }

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_action(
            "rest_api_init",
            $this,
            "register_endpoints",
        );
    }

    final protected function add_endpoint(PWP_I_Endpoint $endpoint): void
    {
        $this->endpoints[] = $endpoint;
    }

    public function register_endpoints(): void
    {
        foreach ($this->endpoints as $endpoint) {
            $this->register_endpoint($endpoint);
        }
    }

    public function register_endpoint(PWP_I_Endpoint $endpoint): void
    {
        register_rest_route(
            $this->namespace,
            $endpoint->get_path(),
            array(
                'args' => $endpoint->get_arguments(),
                'callback' => $endpoint->get_callback(),
                'methods' => $endpoint->get_methods(),
                'permission_callback' => $endpoint->get_permission_callback(),
            )
        );
    }
}
