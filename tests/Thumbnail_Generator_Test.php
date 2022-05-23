<?php

declare(strict_types=1);

namespace PWP\Tests;

use PHPUnit\Framework\TestCase;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\utilities\PWP_Thumbnail_Generator;

class Thumbnail_Generator_Test extends TestCase
{
    public function test_class_construction(): void
    {
        $generator = new PWP_Thumbnail_Generator();

        $this->assertIsObject($generator, "outcome is not an object!");
    }

    public function image_data_provider(): array
    {
        $resourceFolder = WP_PLUGIN_DIR . '/Peleman-Webshop-Package/resources/';
        return array(
            array(
                $resourceFolder . 'lena.png',
                $resourceFolder . 'bar_001.png', 0, 64, null, true
            ),
            array(
                $resourceFolder . 'lena.jpg',
                $resourceFolder . 'baz_001.png', 9, 128, null, true
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
        int $quality,
        int $width,
        ?int $height,
        bool $expected
    ) {
        $generator = new PWP_Thumbnail_Generator();
        $outcome = $generator->generate_png_thumbnail($src, $dest, $quality, $width, $height);

        $this->assertIsBool($outcome, "output value: {$outcome}!");
        $this->assertEquals($expected, $outcome, "outcomes do not match!");

        $this->assertFileExists($dest);
        // wp_delete_file($dest);
    }

    /**
     * @dataProvider faulty_data_provider
     *
     * @param string $src
     * @param string $expected
     * @return void
     */
    public function test_get_image(string $src, string $dest, \Exception $expected): void
    {
        $this->expectException(get_class($expected));
        $generator = new PWP_Thumbnail_Generator();
        $image = $generator->generate_png_thumbnail($src, $dest, 0, 50);
    }

    public function faulty_data_provider(): array
    {
        $resourceFolder = WP_PLUGIN_DIR . '/Peleman-Webshop-Package/resources/';
        return array(
            array(
                $resourceFolder . 'Frank.png',
                $resourceFolder . 'Frank2.png', new PWP_Not_Found_Exception()
            ),
        );
    }
}
