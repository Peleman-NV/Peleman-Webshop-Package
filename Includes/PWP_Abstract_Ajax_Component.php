<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\hookables\PWP_I_Hookable_Component;

abstract class PWP_Abstract_Ajax_Component implements PWP_I_Hookable_Component
{

    protected string $nonceName;
    protected string $scriptHandle;
    protected string $objectName;
    protected string $jsFilePath;

    private const CALLBACK = 'callback';
    private const CALLBACK_NOPRIV = 'callback_nopriv';

    /**
     * Undocumented function
     *
     * @param string $nonceName name of the nonce for the Ajax call. Used for verifying a source and preventing replay attacks.
     * @param string $handle script handle to which data will be attached.
     * @param string $objectName name for the Javascript object that is to be passed directly.
     * @param string $jsFilePath path of the Javascript file relative to the component file location.
     */
    public function __construct(string $nonceName, string $handle, string $objectName, string $jsFilePath)
    {
        $this->nonceName = $nonceName;
        $this->scriptHandle = $handle;
        $this->objectName = $objectName;
        $this->jsFilePath = $jsFilePath;
        echo $this->jsFilePath;
    }

    final public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_action('wp_enqueue_scripts', $this, 'enqueue_ajax', 8);

        $loader->add_ajax_action(
            $this->objectName,
            $this,
            self::CALLBACK
        );
        $loader->add_ajax_nopriv_action(
            $this->objectName,
            $this,
            self::CALLBACK_NOPRIV
        );
    }

    final public function enqueue_ajax(): void
    {
        wp_enqueue_script(
            $this->scriptHandle,
            $this->jsFilePath,
            array('jquery'),
            rand(0, 2000),
            true
        );
        wp_localize_script(
            $this->scriptHandle,
            $this->objectName,
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonceName' => wp_create_nonce($this->nonceName)
            )
        );
    }

    /**
     * functionality of the component for authenticated users
     *
     * @return void
     */
    public abstract function callback(): void;

    /**
     * functionality of the component for unauthenticated users
     *
     * @return void
     */
    public abstract function callback_nopriv(): void;
}
