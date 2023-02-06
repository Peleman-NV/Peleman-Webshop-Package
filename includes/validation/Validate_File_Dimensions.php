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
    public function __construct(int $height, int $width, float $precision = .5)
    {
        parent::__construct();
        $this->heightRange = $height;
        $this->widthRange = $width;
        $this->precision = $precision;
    }

    public function handle(PDF_Upload $data, ?I_Notification $notification = null): bool
    {
        // error_log('height: ' . $data->get_height());
        // error_log('width: ' . $data->get_width());

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
        return $precision >= abs($value - $range);
    }
}
