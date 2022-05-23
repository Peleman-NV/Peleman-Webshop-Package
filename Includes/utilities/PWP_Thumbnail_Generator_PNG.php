<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use Throwable;

class PWP_Thumbnail_Generator_PNG extends PWP_Abstract_Thumbnail_Generator
{
    protected string $suffix = '.png';

    public function __construct(int $quality = -1)
    {
        if ($quality >= 0) {
            $quality = $this->map_quality($quality);
        }
        parent::__construct($quality);
    }

    public function generate(string $src, string $dest, string $name, int $tgtWidth, ?int $tgtHeight = null, ?int $quality = null): void
    {
        try {
            $thumbnail = $this->generate_thumbnail(
                $this->get_image($src),
                $tgtWidth,
                $tgtHeight
            );

            imagesavealpha($thumbnail, false);

            $quality = !is_null($quality) ? $this->map_quality($quality) : $this->quality;
            if (!imagepng($thumbnail, $dest . '/' . $name . $this->suffix, $quality)) {
                throw new PWP_Invalid_Input_Exception('Image could not be made for unknown reasons');
            }
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function map_quality(int $quality): int
    {
        return (int)round(self::map($quality, 0, 100, 0, 9), 0);
    }
}
