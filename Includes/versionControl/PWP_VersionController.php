<?php

declare(strict_types=1);

namespace PWP\includes\versionControl;

use Exception;
use wpdb;
use PWP\includes\versionControl\PWP_Update;
use PWP\includes\versionControl\PWP_VersionNumber;

class PWP_VersionController
{
    /**
     * version of the current plugin, and the version number the version controller is trying to reach
     */
    private PWP_VersionNumber $pluginVersion;
    /**
     * version number of the local version, and what we're trying to upgrade
     */
    private PWP_VersionNumber $localVersion;

    /**
     * @var PWP_Update[]
     */
    private array $updates;

    public function __construct(string $pluginVersion, string $localVersion)
    {
        $this->pluginVersion = PWP_VersionNumber::from_string($pluginVersion);
        $this->localVersion = PWP_VersionNumber::from_string($localVersion);
        $this->updates = array();
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

        $this->updates[] = new PWP_ExampleUpdate('0.0.3');
        $this->updates[] = new PWP_ExampleUpdate('0.0.15');
        $this->updates[] = new PWP_ExampleUpdate('0.1.2');
        $this->updates[] = new PWP_ExampleUpdate('0.2.0');

        //just to be sure, sort array of updates by version number (from oldest to newest);
        uasort($this->updates, function (PWP_Update $a, PWP_Update $b) {
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
        } catch (\Exception $error) {
            //undo last upgrade we tried to do
            $this->updates[$key]->downgrade();

            //throw error back into the wild
            throw $error;
        }

        //when all updates have been performed successfully, update the local plugin version to the current version
        update_option('pwp-version', (string)$this->pluginVersion);
    }

    private function run_update(PWP_Update $update, PWP_VersionNumber $latestVersion): PWP_VersionNumber
    {
        if ($update->is_newer_than($latestVersion)) {
            return $update->upgrade();
        }
        return $latestVersion;
    }
}
