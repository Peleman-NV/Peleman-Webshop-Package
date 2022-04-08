<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\hookables\PWP_IHookable;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\API\endpoints\PWP_IEndpoint;
use PWP\includes\API\endpoints\PWP_Test_Endpoint;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Products_Endpoint;


defined('ABSPATH') || die;

/**
 * Hookable component of the Peleman Webshop Package plugin, responsible for the REST API component
 */
class PWP_API_Hookable implements PWP_IHookable
{
    protected string $namespace;
    protected string $rest_base;
    protected array $endpoints;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace ?: 'pwp/v1';
        $authenticator = new PWP_Authenticator();

        $this->add_endpoint(new PWP_Test_Endpoint($this->namespace, $authenticator));
        $this->add_endpoint(new PWP_Products_Endpoint($this->namespace, $authenticator));

        //TODO: add endpoints
        //  attributes
        //  tags
        //  images
        //  categories
        //  variations
        //  terms
        //  menus
    }

    public function register(PWP_Plugin_Loader $loader): void
    {
        $loader->add_action(
            "rest_api_init",
            $this,
            "init_endpoints",
        );
    }

    protected function add_endpoint(PWP_IEndpoint $endpoint): void
    {
        $this->endpoints[] = $endpoint;
    }

    public function init_endpoints(): void
    {
        foreach ($this->endpoints as $endpoint) {
            $endpoint->register();
        }
    }
}