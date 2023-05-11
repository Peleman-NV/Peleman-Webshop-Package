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
            $data->get_height(),
            $this->heightRange,
            $this->precision
        );

        $widthFit = $this->value_is_in_range(
            $data->get_width(),
            $this->widthRange,
            $this->precision
        );
        error_Log('page count: ' . $data->get_page_count());
        error_log('width: ' . $data->get_width() . ' mm');
        error_log('height: ' . $data->get_height()  . ' mm');
        if ($heightFit && $widthFit) {
            return $this->handle_next($data, $notification);
        }
        $notification->add_error(
            'Dimensions not valid',
            sprintf(
                __('The dimensions of the file do not match the specified dimensions \n your file:  %d mm by %d mm, requires : %d mm by %d mm', 'Peleman-Webshop-Package'),
                number_format($data->get_width(), 1),
                number_Format($data->get_height(), 1),
                $this->widthRange,
                $this->heightRange,
            )
        );
        return false;
    }

    private function value_is_in_range(float $value, float $range, float $precision): bool
    {
        if ($range === 0) return true;
        return $precision >= abs($value - $range);
    }
}
