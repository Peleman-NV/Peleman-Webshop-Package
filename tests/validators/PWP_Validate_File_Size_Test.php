<?php

declare(strict_types=1);

namespace PWP\tests\validators;

use PHPUnit\Framework\TestCase;
use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\utilities\notification\PWP_Notification;
use PWP\includes\validation\PWP_Validate_File_Size;
use PWP\includes\wrappers\PWP_PDF_Upload;

class PWP_Validate_File_Size_Test extends TestCase
{
    public function file_data_provider(): array
    {
        $notification = new PWP_Notification('');

        return array(
            array(new PWP_PDF_Upload(['size' => 8]), $notification, 12, true),
            array(new PWP_PDF_Upload(['size' => 10]), $notification, 10, true),
            array(new PWP_PDF_Upload(['size' => 12]), $notification, 10, false),
        );
    }

    /**
     * Undocumented function
     * @dataProvider  file_data_provider
     * @return void
     */
    public function test_size_validation(PWP_PDF_Upload $data, PWP_I_Notification $notification, int $maxFileSize, bool $expected): void
    {
        $validator = new PWP_Validate_File_Size($maxFileSize);
        $this->assertEquals($expected, $validator->handle($data, $notification));
    }
}
