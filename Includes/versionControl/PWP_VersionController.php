<?php

declare(strict_types=1);

namespace PWP\includes\versionControl;

use Exception;
use wpdb;
use PWP\includes\versionControl\PWP_Update;
use PWP\includes\versionControl\PWP_VersionNumber;

class PWP_VersionController
{
    private PWP_VersionNumber $localVersion; //version the local version is at
    private PWP_VersionNumber $pluginVersion; //version of the current plugin, and the number we are trying to get to.

    /**
     * @var PWP_Update[]
     */
    private array $updates;

    public function __construct(string $pluginVersion, string $localVersion)
    {
        $this->pluginVersion = PWP_VersionNumber::from_string($pluginVersion);
        $this->localVersion = PWP_VersionNumber::from_string($localVersion);
    }

    public function try_update()
    {
        if ($this->pluginVersion->is_newer_than($this->localVersion)) {
            $this->register_updates();
            $this->upgrade_to_newest_version();
        }
    }

    private function register_updates(): void
    {
        //here we register all the update objects
        //this way we ensure we only load and register these objects when we are trying to upgrade the local version

        $updates[] = new PWP_ExampleUpdate();

        //just to be sure, sort array of updates by version number (from oldest to newest);
        uasort($updates, function (PWP_Update $a, PWP_Update $b) {
            return $a->compare_version($b);
        });
    }

    private function upgrade_to_newest_version(): void
    {
        $latestVersion = $this->localVersion;
        $key = 0;
        try {
            foreach ($this->updates as $key => $update) {
                $latestVersion = $this->run_update($update, $latestVersion);
            }

            update_option('pwp-version', (string)$latestVersion);
        } catch (\Exception $error) {
            //undo last upgrade we tried to do
            $this->updates[$key]->downgrade();

            //throw error back into the wild
            throw $error;
        }

        if (!$latestVersion->is_older_than($this->pluginVersion)) {
            throw new Exception("something went wrong with the updating process leading to a version mismatch. Likely you are missing an upgrade object script, or the latest has been deleted", 500);
        }
    }

    private function run_update(PWP_Update $update): PWP_VersionNumber
    {
        if ($update->is_newer_than($this->localVersion)) {
            $newVersion = $update->upgrade();
        }

        return $newVersion;
    }
}
