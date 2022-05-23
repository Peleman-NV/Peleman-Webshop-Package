<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

interface PWP_I_Thumbnail_Generator
{
    /**
     * Generate thumbnail file from existing image
     *
     * @param string $src file path of the source image.
     * @param string $dest file path of the thumbnail destination folder.
     * @param string $name name of the file without extension.
     * @param integer $tgtWidth target width of the final thumbnail.
     * @param integer|null $tgtHeight target height of the final thumbnail. if left `null`, will retain the aspect ratio of the original.
     * @param integer|null $quality see constructor for details. if left `null`, will use the class's quality parameter.
     *  @return void
     */
    public function generate(string $src, string $dest, string $name, int $tgtWidth, int $tgtHeight = null, int $quality = null): void;
}
