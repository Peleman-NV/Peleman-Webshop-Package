<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use Throwable;

use function PHPUnit\Framework\isNull;

class PWP_Thumbnail_Generator
{
    public function __construct()
    {
    }

    public function generate_png_thumbnail(string $src, string $dest, int $quality, int $targetWidth, int $targetHeight = null): bool
    {
        try {
            $thumbnail = $this->generate_thumbnail($this->get_image($src), $targetWidth, $targetHeight);
            return imagepng($thumbnail, $dest, $quality);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function get_image(string $src)
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

    private function generate_thumbnail($image, int $targetWidth, ?int $targetHeight = null)
    {
        $srcWidth = imagesx($image);
        $imgHeight = imagesy($image);

        $aspectRatio = $srcWidth / $imgHeight;
        $targetHeight = $targetHeight ?: (int)floor($targetWidth * $aspectRatio);
        $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

        imagecopyresampled(
            $thumbnail,
            $image,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $srcWidth,
            $imgHeight
        );

        return $thumbnail;
    }
}
