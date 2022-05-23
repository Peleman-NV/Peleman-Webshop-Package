<?php

declare(strict_types=1);

namespace PWP\Tests;

use PHPUnit\Framework\TestCase;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\utilities\PWP_Thumbnail_Generator_JPG;
use PWP\includes\utilities\PWP_Thumbnail_Generator_PNG;

class Thumbnail_Generator_Test extends TestCase
{
    public function test_class_construction(): void
    {
        $generator = new PWP_Thumbnail_Generator_PNG(0);

        $this->assertIsObject($generator, "outcome is not an object!");
    }

    public function image_data_provider(): array
    {
        $resourceFolder = WP_PLUGIN_DIR . '/Peleman-Webshop-Package/resources/';
        return array(
            array(
                $resourceFolder . 'lena.png',
                $resourceFolder, 'bar_001', 64, null, null,
            ),
            array(
                $resourceFolder . 'lena.jpg',
                $resourceFolder, 'baz_001.png', 128, null, null,
            ),
        );
    }

    /**
     * Undocumented function
     * @dataProvider image_data_provider
     * @param string $sourceImg
     * 
     * @param string $destination
     * @param boolean $expected
     * @return void
     */
    public function test_generate_png_thumbnail(
        string $src,
        string $dest,
        string $filename,
        int $width,
        ?int $height,
        ?int $quality
    ): void {
        $generator = new PWP_Thumbnail_Generator_PNG(0);
        $generator->generate($src, $dest, $filename, $width, $height, $quality);

        $filePath = $dest . '/' . $filename . $generator::SUFFIX;
        $this->assertFileExists($filePath);

        $img = imagecreatefrompng($filePath);
        $this->assertEquals(imagesx($img), $width);
        // wp_delete_file($dest);
    }

    /**
     * @dataProvider image_data_provider
     *
     * @param string $src
     * @param string $dest
     * @param string $filename
     * @param integer $width
     * @param integer|null $height
     * @param integer|null $quality
     * @return void
     */
    public function test_generate_jpeg_thumbnail(
        string $src,
        string $dest,
        string $filename,
        int $width,
        ?int $height,
        ?int $quality
    ): void {
        $generator = new PWP_Thumbnail_Generator_JPG(0);
        $generator->generate($src, $dest, $filename, $width, $height, $quality);

        $filePath = $dest . '/' . $filename . $generator::SUFFIX;
        $this->assertFileExists($filePath);

        $img = imagecreatefromjpeg($filePath);
        $this->assertEquals(imagesx($img), $width);
        // wp_delete_file($dest);
    }

    /**
     * @dataProvider faulty_data_provider
     *
     * @param string $src
     * @param \Exception $expected
     * @return void
     */
    public function test_exceptions_png(string $src, string $dest, string $name, \Exception $expected): void
    {
        $this->expectException(get_class($expected));
        $generator = new PWP_Thumbnail_Generator_PNG(0);
        $image = $generator->generate($src, $dest, $name, 0, 50);
    }

    /**
     * @dataProvider faulty_data_provider
     *
     * @param string $src
     * @param \Exception $expected
     * @return void
     */
    public function test_exceptions_jpg(string $src, string $dest, string $name, \Exception $expected): void
    {
        $this->expectException(get_class($expected));
        $generator = new PWP_Thumbnail_Generator_JPG(0);
        $image = $generator->generate($src, $dest, $name, 0, 50);
    }

    public function faulty_data_provider(): array
    {
        $resourceFolder = WP_PLUGIN_DIR . '/Peleman-Webshop-Package/resources/';
        return array(
            array(
                $resourceFolder . 'Frank.png',
                $resourceFolder, 'Frank2', new PWP_Not_Found_Exception()
            ),
        );
    }
}
