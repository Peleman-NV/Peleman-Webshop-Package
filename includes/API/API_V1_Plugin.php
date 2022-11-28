<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\API\endpoints\attributes\API_Attributes_Channel;
use PWP\includes\authentication\Authenticator;
use PWP\includes\traits\Hookable_Parent_Trait;
use PWP\includes\hookables\abstracts\I_Hookable_Component;

use PWP\includes\API\endpoints\categories\API_Categories_Channel;
use PWP\includes\API\endpoints\products\API_Products_Channel;
use PWP\includes\API\endpoints\FIND_PDF_Endpoint;
use PWP\includes\API\endpoints\FIND_Project_Thumbnail;
use PWP\includes\API\endpoints\Test_Endpoint;

/**
 * overarching class which contains and handles the creation/registering of API Channels
 */
class API_V1_Plugin implements I_Hookable_Component
{
    private string $namespace;

    /**
     * @var I_Hookable_Component[]
     */
    private array $hookables;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $authenticator = new Authenticator();

        $this->add_hookable(new FIND_PDF_Endpoint($this->namespace, $authenticator));
        $this->add_hookable(new FIND_Project_Thumbnail($this->namespace, $authenticator));

        //temporarily disabled: under development
        // $this->add_hookable(new API_Categories_Channel($this->namespace, '', $authenticator));
        // $this->add_hookable(new API_Attributes_Channel($this->namespace, '', $authenticator));
        // $this->add_hookable(new API_Products_Channel($this->namespace, '', $authenticator));
    }

    public function register(): void
    {
        \add_action('rest_api_init', array($this, 'register_hookables'), 10, 1);
    }

    public function add_hookable(I_Hookable_Component $hookable): void
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
