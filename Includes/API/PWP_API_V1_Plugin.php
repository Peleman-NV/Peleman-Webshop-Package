<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\API\endpoints\attributes\PWP_API_Attributes_Channel;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\traits\PWP_Hookable_Parent_Trait;
use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;

use PWP\includes\API\endpoints\categories\PWP_API_Categories_Channel;
use PWP\includes\API\endpoints\Products\PWP_API_Products_Channel;
use PWP\includes\API\endpoints\PWP_FIND_PDF_Endpoint;
use PWP\includes\API\endpoints\PWP_Test_Endpoint;

/**
 * overarching class which contains and handles the creation/registering of API Channels
 */
class PWP_API_V1_Plugin implements PWP_I_Hookable_Component
{
    private string $namespace;

    /**
     * @var PWP_I_Hookable_Component[]
     */
    private array $hookables;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $authenticator = new PWP_Authenticator();

        $this->add_hookable(new PWP_FIND_PDF_Endpoint($this->namespace, $authenticator));

        $this->add_hookable(new PWP_API_Categories_Channel($this->namespace, '', $authenticator));
        $this->add_hookable(new PWP_API_Attributes_Channel($this->namespace, '', $authenticator));
        //temporarily disabled: under development
        // $this->add_hookable(new PWP_API_Products_Channel($this->namespace, '', $authenticator));
    }

    public function register(): void
    {
        \add_action('rest_api_init', array($this, 'register_hookables'), 10, 1);
    }

    public function add_hookable(PWP_I_Hookable_Component $hookable): void
    {
        $this->hookables[] = $hookable;
    }

    public function register_hookables(): void
    {
        foreach ($this->hookables as $hookable) {
            $hookable->register();
        }
    }
}
