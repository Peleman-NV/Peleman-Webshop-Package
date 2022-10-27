<?php

declare(strict_types=1);

namespace PWP\tests;

use PHPUnit\Framework\TestCase;
use PWP\includes\exceptions\Not_Found_Exception;
use PWP\includes\utilities\Thumbnail_Generator_JPG;
use PWP\includes\utilities\Thumbnail_Generator_PNG;

class Thumbnail_Generator_Test extends TestCase
{
    public function test_class_construction(): void
    {
        $generator = new Thumbnail_Generator_PNG();

        $this->assertIsObject($generator, "outcome is not an object!");
    }

    public function img_data_provider(): array
    {
        $resourceFolder = WP_PLUGIN_DIR . '/Peleman-Webshop-Package/resources/';
        return array(
            array(
                $resourceFolder . 'airplane.png',
                $resourceFolder, 'bar_1', 64, null, null,
            ),
            array(
                $resourceFolder . 'airplane.jpg',
                $resourceFolder, 'bar_2', 64, null, null,
            ),
            array(
                $resourceFolder . 'airplane.png',
                $resourceFolder, 'baz_Q00', 64, null, 0,
            ),
            array(
                $resourceFolder . 'airplane.png',
                $resourceFolder, 'baz_Q25', 64, null, 25,
            ),
            array(
                $resourceFolder . 'airplane.png',
                $resourceFolder, 'baz_Q50', 64, null, 50,
            ),
            array(
                $resourceFolder . 'airplane.png',
                $resourceFolder, 'baz_Q75', 64, null, 75,
            ),
            array(
                $resourceFolder . 'airplane.png',
                $resourceFolder, 'baz_Q100', 64, null, 100,
            ),
        );
    }

    /**
     * Undocumented function
     * @dataProvider img_data_provider
     * @param string $sourceImg
     * 
     * @param string $destination
     * @param boolean $expected
     * @return void
     */
    public function test_generate_png_thumbnail(
        string $src,
        string $dest,
        string $fiairplaneme,
        int $width,
        ?int $height,
        ?int $quality
    ): void {
        $generator = new Thumbnail_Generator_PNG(0);
        $filePath = $generator->generate($src, $dest, $fiairplaneme, $width, $height, $quality);

        $this->assertFileExists($filePath);

        $img = imagecreatefrompng($filePath);
        $this->assertEquals(imagesx($img), $width);
        // wp_delete_file($dest);
    }

    /**
     * @dataProvider img_data_provider
     *
     * @param string $src
     * @param string $dest
     * @param string $fiairplaneme
     * @param integer $width
     * @param integer|null $height
     * @param integer|null $quality
     * @return void
     */
    public function test_generate_jpeg_thumbnail(
        string $src,
        string $dest,
        string $fiairplaneme,
        int $width,
        ?int $height,
        ?int $quality
    ): void {
        $generator = new Thumbnail_Generator_JPG();
        $filePath = $generator->generate($src, $dest, $fiairplaneme, $width, $height, $quality);

        $img = imagecreatefromjpeg($filePath);
        $this->assertEquals(imagesx($img), $width);
        // wp_delete_file($dest);
    }
    
    public function faulty_data_provider(): array
    {
        $resourceFolder = WP_PLUGIN_DIR . '/Peleman-Webshop-Package/resources/';
        return array(
            array(
                $resourceFolder . 'Frank.png',
                $resourceFolder, 'Frank2', new Not_Found_Exception()
            ),
        );
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
        $generator = new Thumbnail_Generator_PNG(0);
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
        $generator = new Thumbnail_Generator_JPG(0);
        $image = $generator->generate($src, $dest, $name, 0, 50);
    }
}
