<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PDF_Upload;
use PWP\includes\utilities\notification\I_Notification;

class Validate_File_Dimensions extends Abstract_File_Handler
{
    private int $heightRange;
    private int $widthRange;
    private float $precision;
    private const USER_UNIT = 0.3528;
    public function __construct(int $height, int $width, float $precision = 5)
    {
        parent::__construct();
        $this->heightRange = $height;
        $this->widthRange = $width;
        $this->precision = $precision;
    }

    public function handle(PDF_Upload $data, ?I_Notification $notification = null): bool
    {
        $heightFit = $this->value_is_in_range(
            $data->get_height() * self::USER_UNIT,
            $this->heightRange,
            $this->precision
        );

        $widthFit = $this->value_is_in_range(
            $data->get_width() * self::USER_UNIT,
            $this->widthRange,
            $this->precision
        );
        error_Log('page count: ' . $data->get_page_count());
        error_log('width: ' . $data->get_width()  * self::USER_UNIT . ' mm');
        error_log('height: ' . $data->get_height()  * self::USER_UNIT . ' mm');
        if ($heightFit && $widthFit) {
            return $this->handle_next($data, $notification);
        }
        $notification->add_error(
            'Dimensions not valid',
            __('The dimensions of the file do not match the specified dimensions', 'Peleman-Webshop-Package')
        );
        return false;
    }

    private function value_is_in_range(float $value, float $range, float $precision): bool
    {
        if ($range === 0) return true;
        error_log('' . $precision);
        error_log('' . abs($value - $range));
        return $precision >= abs($value - $range);
    }
}
