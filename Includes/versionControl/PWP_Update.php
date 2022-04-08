<?php

declare(strict_types=1);

namespace PWP\includes\versionControl;

use PWP\includes\versionControl\PWP_VersionNumber;

abstract class PWP_Update
{
    private PWP_VersionNumber $version;

    public function __construct(string $version)
    {
        $this->version = PWP_VersionNumber::from_string($version);
    }

    /**
     * runs internal upgrade logic
     * 
     * @return PWP_VersionNumber  returns new version number.
     */
    public abstract function upgrade(): PWP_VersionNumber;

    /**
     * runs internal downgrade logic
     *
     * @return void
     */
    public abstract function downgrade(): void;


    final public function is_newer_than(PWP_VersionNumber $version): bool
    {
        return $this->version->is_newer_than($version);
    }

    final public function get_version_number(): PWP_VersionNumber
    {
        return $this->version;
    }

    final public function compare_version(PWP_Update $update): int
    {
        return $this->version->compare($update->version);
    }
}
