<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;

abstract class PWP_Abstract_Thumbnail_Generator implements PWP_I_Thumbnail_Generator
{
    protected int $quality;
    protected string $suffix = "";
    /**
     * class to generate thumbnails from existing files.
     *
     * @param integer $quality should be an integer value from 0 (no compression), 1 (fastest compression) to 100 (best compression). Default value is -1 (default compression)
     */
    public function __construct(int $quality = -1)
    {
        $this->quality = min(100, max($quality, -1));
    }
    public abstract function generate(string $src, string $dest, string $name, int $tgtWidth, int $tgtHeight = null, int $quality = null): void;


    /**
     * Undocumented function
     *
     * @param string $src
     * @return resource|\GdImage
     * @throws PWP_Not_Found_Exception
     * @throws PWP_Invalid_Input_Exception
     */
    protected function get_image(string $src)
    {
        if (!file_exists($src)) {
            throw new PWP_Not_Found_Exception("Could not find image");
        }
        $type = exif_imagetype($src);
        $image = null;

        switch ($type) {
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($src);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($src);
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($src);
                break;
            default:
                throw new PWP_Invalid_Input_Exception("image file not currently supported");
        }
        if (!$image) {
            throw new PWP_Invalid_Input_Exception("Something went wrong in reading the image from file.");
        }
        return $image;
    }

    /**
     * Undocumented function
     *
     * @param resource|\GdImage $image
     * @param integer $tgtWidth target width of thumbnail
     * @param integer|null $tgtHeight target height of thumbnail. default `null`, height will be based on target width and aspect ratio
     * @return resource|\GdImage
     */
    protected function generate_thumbnail($image, int $tgtWidth, ?int $tgtHeight = null)
    {
        $srcWidth = imagesx($image);
        $srcHeight = imagesy($image);

        $aspectRatio = $srcWidth / $srcHeight;
        $tgtHeight = $tgtHeight ?: (int)floor($tgtWidth * $aspectRatio);
        $thumbnail = imagecreatetruecolor($tgtWidth, $tgtHeight);

        imagecopyresampled(
            $thumbnail,
            $image,
            0,
            0,
            0,
            0,
            $tgtWidth,
            $tgtHeight,
            $srcWidth,
            $srcHeight
        );

        return $thumbnail;
    }

    public function get_suffix(): string
    {
        return $this->suffix;
    }

    protected static function map(int $value, int $fromLow, int $fromHigh, int $toLow, int $toHigh): float
    {
        $fromRange = $fromHigh - $fromLow;
        $toRange = $toHigh - $toLow;
        $scale = $toRange / $fromRange;

        $value -= $fromLow;
        $value *= $scale;
        $value += $toLow;

        return $value;
    }
}
