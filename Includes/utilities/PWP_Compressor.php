<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

abstract class PWP_Compressor{

    public abstract function compressFiles(string $archiveName, array $files) : void;
}