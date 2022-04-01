<?php

declare(strict_types=1);

namespace PPA\includes\hookables;

use PPA\includes\endpoints\PPA_IEndpoint;
use PPA\includes\loaders\PPA_Plugin_Loader;
use PPA\includes\endpoints\PPA_Test_Endpoint;
use PPA\includes\authentication\PPA_Authenticator;

defined('ABSPATH') || die;

class PPA_API_Hookable implements PPA_IHookable
{
    protected string $namespace;
    protected string $rest_base;
    protected array $endpoints;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace ?: 'ppa/v1';
        $authenticator = new PPA_Authenticator();

        $this->add_endpoint(new PPA_Test_Endpoint($this->namespace, $authenticator));
    }

    public function register(PPA_Plugin_Loader $loader): void
    {
        $loader->add_action(
            "rest_api_init",
            $this,
            "init_endpoints"
        );
    }

    protected function add_endpoint(PPA_IEndpoint $endpoint): void
    {
        $this->endpoints[] = $endpoint;
    }

    protected function init_endpoints(): void
    {
        /**
         * @var PPA_IEndpoint
         */
        foreach($this->endpoints as $endpoint)
        {
            $endpoint->register();
        }
    }
}
