<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\API\endpoints\PWP_IEndpoint;
use PWP\includes\API\endpoints\PWP_Tags_Endpoint;
use PWP\includes\API\endpoints\PWP_Test_Endpoint;
use PWP\includes\API\endpoints\PWP_Menus_Endpoint;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\hookables\PWP_IHookableComponent;
use PWP\includes\API\endpoints\PWP_Images_Endpoint;
use PWP\includes\API\endpoints\PWP_Orders_Endpoint;
use PWP\includes\API\endpoints\PWP_Products_Endpoint;
use PWP\includes\API\endpoints\PWP_Customers_Endpoint;
use PWP\includes\API\endpoints\PWP_Attributes_Endpoint;
use PWP\includes\API\endpoints\PWP_Categories_Endpoint;
use PWP\includes\API\endpoints\PWP_Attribute_Terms_Endpoint;
use PWP\includes\API\endpoints\PWP_Languages_Endpoint;
use PWP\includes\API\endpoints\PWP_Product_Variations_Endpoint;
use PWP\includes\authentication\PWP_IApiAuthenticator;

defined('ABSPATH') || die;

/**
 * Hookable component of the Peleman Webshop Package plugin, responsible for the REST API component
 */
class PWP_API_Hookable implements PWP_IHookableComponent
{
    protected string $namespace;
    protected string $rest_base;
    protected array $endpoints;
    protected PWP_IApiAuthenticator $authenticator;

    public function __construct(?string $namespace)
    {
        $this->namespace = $namespace ?: 'pwp/v1';
        $this->authenticator = new PWP_Authenticator();

        //TODO: look into why this endpoint specifically crashes the wp-admin panel
        // $this->add_endpoint(new PWP_Products_Endpoint());
        $this->add_endpoint(new PWP_Product_Variations_Endpoint());

        $this->add_endpoint(new PWP_Tags_Endpoint());
        $this->add_endpoint(new PWP_Categories_Endpoint());
        $this->add_endpoint(new PWP_Attributes_Endpoint());
        $this->add_endpoint(new PWP_Attribute_Terms_Endpoint());

        $this->add_endpoint(new PWP_Images_Endpoint());
        $this->add_endpoint(new PWP_Customers_Endpoint());
        $this->add_endpoint(new PWP_Orders_Endpoint());

        $this->add_endpoint(new PWP_Menus_Endpoint());

        //Testing Endpoints; to be removed when no longer relevant
        $this->add_endpoint(new PWP_Test_Endpoint());
        $this->add_endpoint(new PWP_Languages_Endpoint());
    }

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_action(
            "rest_api_init",
            $this,
            "init_endpoints",
        );
    }

    final protected function add_endpoint(PWP_IEndpoint $endpoint): void
    {
        $this->endpoints[] = $endpoint;
    }

    public function init_endpoints(): void
    {
        foreach ($this->endpoints as $endpoint) {
            $endpoint->register_routes($this->namespace, $this->authenticator);
        }
    }
}
