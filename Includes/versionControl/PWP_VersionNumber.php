<?php

declare(strict_types=1);

namespace PWP\includes\versionControl;

class PWP_VersionNumber
{
    public int $major;
    public int $minor;
    public int $patch;
    public string $rest;

    private function __construct(int $major, int $minor, int $patch, string $rest = '')
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
        $this->rest = $rest;
    }

    /**
     * static factory function to create version number object from string.
     *
     * @param string $version number as string. MUST be semantic, and in the format of ```INT.INT.INT.STRING```, separated by periods 
     * @return PWP_VersionNumber
     */
    public static function from_string(string $version): PWP_VersionNumber
    {
        $version = explode('.', $version, 4);
        return new PWP_VersionNumber((int)$version[0], (int)$version[1], (int)$version[2], $version[3]);
    }

    /**
     * static factory function to create version number object from integers
     *
     * @param integer $major
     * @param integer $minor
     * @param integer $patch
     * @param string $rest
     * @return PWP_VersionNumber
     */
    public static function from_ints(int $major, int $minor, int $patch, string $rest = ''): PWP_VersionNumber
    {
        return new PWP_VersionNumber($major, $minor, $patch);
    }

    public function is_newer_than(PWP_VersionNumber $other): bool
    {
        return $this->to_int() > $other->to_int();
    }

    public function is_older_than(PWP_VersionNumber $other): bool
    {
        return $this->to_int() < $other->to_int();
    }

    public function equals(PWP_VersionNumber $other): bool
    {
        return $this->to_int() === $other->to_int();
    }

    /**
     * comparison function for array sorting
     *
     * @param PWP_VersionNumber $other
     * @param bool $reverse default false, will invert results from what is described. quick way to reverse-sort if desired.
     * @return integer ```-1``` if this version is OLDER than other, ```0``` if they MATCH, and ```1``` if this version is NEWER
     */
    public function compare(PWP_VersionNumber $other, bool $reverse = false): int
    {
        if($this->equals($other)) return 0;
        if($this->is_newer_than($other)) return $reverse? -1 :1;
        else return $reverse? 1 : -1;
    }

    /**
     * helper function to return version number as an integer for easier comparison
     * 
     * resulting int will be of format ```Major * 10000 + Minor * 100 + Patch```
     * we do this to allow for extra padding when the version numbers reach double digits.
     * It is thus not recommended to go over double digits with version numbering
     *
     * @return integer
     */
    private function to_int(): int
    {
        return $this->major * 10000 + $this->minor * 100 + $this->patch;
    }

    public function __toString()
    {
        return "{$this->major}.{$this->minor}.{$this->patch}.{$this->rest}";
    }
}
