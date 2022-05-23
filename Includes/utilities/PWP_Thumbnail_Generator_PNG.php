<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use Throwable;

class PWP_Thumbnail_Generator_PNG extends PWP_Abstract_Thumbnail_Generator
{
    public const SUFFIX = '.png';

    public function generate(string $src, string $dest, string $name, int $tgtWidth, int $tgtHeight = null, int $quality = null): void
    {
        try {
            $thumbnail = $this->generate_thumbnail(
                $this->get_image($src),
                $tgtWidth,
                $tgtHeight
            );

            imagesavealpha($thumbnail, false);

            if (!imagepng($thumbnail, $dest . '/' . $name . self::SUFFIX, $quality ?: $this->quality)) {
                throw new PWP_Invalid_Input_Exception('Image could not be made for unknown reasons');
            }
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
