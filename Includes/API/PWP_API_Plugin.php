<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\API\endpoints\categories\PWP_API_Categories_Channel;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\hookables\PWP_IHookableComponent;
use PWP\includes\loaders\PWP_Plugin_Loader;

/**
 * overarching class which contains and handles the creation/registering of API Channels
 */
class PWP_API_Plugin implements PWP_IHookableComponent
{
    private string $namespace;
    /**
     * @var PWP_Abstract_API_Channel[]
     */
    private array $channels;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $authenticator = new PWP_Authenticator();
        $this->add_channel(new PWP_API_Categories_Channel($this->namespace));
    }

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        foreach ($this->channels as $channel) {
            $channel->register_hooks($loader);
        }
    }

    final public function add_channel(PWP_Abstract_API_Channel $channel)
    {
        $this->channels[] = $channel;
    }
}
