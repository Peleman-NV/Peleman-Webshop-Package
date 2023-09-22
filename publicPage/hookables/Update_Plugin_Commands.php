<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

/**
 * Experimental system to automatically update WP plugins through PHP scripts.
 * 
 * The system will try to get the latest commit on a branch and then do a composer install.
 */
class Update_Plugin_Commands extends Abstract_Action_Hookable
{
    public const HOOK = 'pwp_update_plugin';
    public function __construct()
    {
        parent::__construct(self::HOOK, 'update');

        if (!wp_next_scheduled(self::HOOK)) {
            wp_schedule_event(time(), 'daily', self::HOOK);
        }
    }

    public function update(): void
    {
        if (!boolval(get_option('pwp_update_automatic', true))) {
            return;
        }
        error_log("update running...");
        $oldPath = getcwd();
        chdir(PWP_DIRECTORY);
        $branch = get_option('pwp_git_update_branch', 'main');
        exec("git checkout {$branch} 2>&1");
        $pull       = exec("git pull https://github.com/Peleman-NV/Peleman-Webshop-Package.git {$branch} 2>&1");
        error_log("git pull: " . print_r($pull, true));

        $install    = exec('composer install --no-dev 2>&1');
        error_log("Composer install result: " . ($install ? "success" : "fail"));
        if ($pull && $install) {
            error_log("Update successful!");
            $this->deregister();
            return;
        }

        error_log("Update failed.");
        chdir($oldPath);
        $this->deregister();
    }
}
