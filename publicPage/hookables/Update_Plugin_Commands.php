<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

/**
 * Experimental system to automatically update WP plugins through PHP scripts.
 * 
 * The system will try to get the latest commit on a branch and then do a composer install.
 * It is recommended to disable this option on a development computer, since composer will do a --no-dev install.
 * This is only intended to keep plugins on dev, demo, and live sites up to date automatically.
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
        /* @phpstan-ignore-next-line */
        chdir(PWP_DIRECTORY);
        $branch = get_option('pwp_git_update_branch', 'main');
        exec("git checkout {$branch} 2>&1");
        $pull       = exec("git pull https://github.com/Peleman-NV/Peleman-Webshop-Package.git {$branch} 2>&1");
        error_log("git pull: " . print_r($pull, true));

        $comp_log = '';
        $install    = exec('composer install --no-dev 2>&1', $comp_log);
        error_log("Composer install result: " . ($install ? "success" : "fail"));
        error_log(print_r($comp_log, true));
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
