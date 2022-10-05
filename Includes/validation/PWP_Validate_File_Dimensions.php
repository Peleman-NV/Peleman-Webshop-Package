<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_File_Data;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validate_File_Dimensions extends PWP_Abstract_File_Handler
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

    public function handle(PWP_File_Data $data, ?PWP_I_Notification $notification = null): bool
    {

        $heightFit = $this->number_is_in_range(
            $data->get_height(),
            $this->heightRange,
            $this->precision
        );

        $widthFit = $this->number_is_in_range(
            $data->get_width(),
            $this->widthRange,
            $this->precision
        );
        if ($heightFit && $widthFit) {
            return $this->handle_next($data, $notification);
        }
        $notification->add_error('Dimensions not valid', 'The dimensions of the file do not match the specified dimensions');
        return false;
    }

    private function number_is_in_range(float $value, float $range, float $precision): bool
    {
        return $precision >= abs($value - $range);
    }
}
