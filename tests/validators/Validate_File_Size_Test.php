<?php

declare(strict_types=1);

namespace PWP\tests\validators;

use PHPUnit\Framework\TestCase;
use PWP\includes\utilities\notification\I_Notification;
use PWP\includes\utilities\notification\Notification;
use PWP\includes\validation\Validate_File_Size;
use PWP\includes\wrappers\PDF_Upload;

class Validate_File_Size_Test extends TestCase
{
    public function file_data_provider(): array
    {
        $notification = new Notification('');

        return array(
            array(new PDF_Upload(['size' => 8]), $notification, 12, true),
            array(new PDF_Upload(['size' => 10]), $notification, 10, true),
            array(new PDF_Upload(['size' => 12]), $notification, 10, false),
        );
    }

    /**
     * Undocumented function
     * @dataProvider  file_data_provider
     * @return void
     */
    public function test_size_validation(PDF_Upload $data, I_Notification $notification, int $maxFileSize, bool $expected): void
    {
        $validator = new Validate_File_Size($maxFileSize);
        $this->assertEquals($expected, $validator->handle($data, $notification));
    }
}
