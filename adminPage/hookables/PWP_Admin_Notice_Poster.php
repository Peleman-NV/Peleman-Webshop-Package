<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\utilities\PWP_Admin_Notice;
use PWP\includes\hookables\PWP_IHookableComponent;

class PWP_Admin_Notice_Poster implements PWP_IHookableComponent
{
    /**
     * Undocumented variable
     *
     * @var PWP_Admin_notice[]
     */
    private array $notices;

    public function __construct()
    {
        $this->notices = array();
    }

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_action('admin_notices', $this, 'display_notices');
    }

    public function add_admin_notice(PWP_Admin_Notice $notice): void
    {
        $this->notices[] = $notice;
    }

    public function display_notices(): void
    {
        foreach($this->notices as $notice)
        {
            printf($notice->get_content());
        }
        $this->clear_notices();
    }

    private function clear_notices(): void
    {
        $this->notices = array();
    }
}
