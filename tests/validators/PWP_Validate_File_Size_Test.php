<?php

declare(strict_types=1);

namespace PWP\Tests\validators;

use PHPUnit\Framework\TestCase;
use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\utilities\notification\PWP_Notification;
use PWP\includes\validation\PWP_Validate_File_Size;
use PWP\includes\wrappers\PWP_File_Data;

class PWP_Validate_File_Size_Test extends TestCase
{
    public function file_data_provider(): array
    {
        $notification = new PWP_Notification('');

        return array(
            array(new PWP_File_Data(['size' => 8]), $notification, 12, true),
            array(new PWP_File_Data(['size' => 10]), $notification, 10, true),
            array(new PWP_File_Data(['size' => 12]), $notification, 10, false),
        );
    }

    /**
     * Undocumented function
     * @dataProvider  file_data_provider
     * @return void
     */
    public function test_size_validation(PWP_File_Data $data, PWP_I_Notification $notification, int $maxFileSize, bool $expected): void
    {
        $validator = new PWP_Validate_File_Size($maxFileSize);
        $this->assertEquals($expected, $validator->handle($data, $notification));
    }
}
