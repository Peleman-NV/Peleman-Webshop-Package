<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\API\endpoints\categories\PWP_API_Categories_Channel;
use PWP\includes\hookables\PWP_IHookableComponent;
use PWP\includes\loaders\PWP_Plugin_Loader;

class PWP_API_Plugin implements PWP_IHookableComponent
{
    private string $namespace;
    /**
     * @var PWP_API_Categories_Channel[]
     */
    private array $channels;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace ?: 'pwp/v1';
        $this->add_channel(new PWP_API_Categories_Channel($this->namespace));
    }

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        foreach ($this->channels as $channel) {
            $channel->register_hooks($loader);
        }
    }

    final public function add_channel(PWP_API_Categories_Channel $channel)
    {
        $this->channels[] = $channel;
    }
}
