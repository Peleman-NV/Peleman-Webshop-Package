<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

class PWP_Input_Fields
{
    public static function create_field(string $id, string $label, string $type,  $value, array $classes = [], string $description = ''): void
    {
        switch ($type) {
            case ('text'):
                self::text_input($id, $label, (string)$value, $classes, $description);
                break;
            case ('number'):
                self::number_input($id, $label, (string)$value, $classes, $description);
                break;
            case ('bool'):
                self::checkbox_input($id, $label, (bool)$value, $classes, $description);
                break;
        }
    }
    public static function text_input(string $id, string $label, string $value, array $classes = [], string $description = ''): void
    {
        \woocommerce_wp_text_input(self::generate_field($id, $label, 'text', $value, $classes, $description));
    }

    public static function number_input(string $id, string $label, string $value, array $classes = [], string $description = ''): void
    {
        \woocommerce_wp_text_input(self::generate_field($id, $label, 'number', $value, $classes, $description));
    }

    public static function color_input(string $id, string $label, string $value, array $classes = [], string $description = ''): void
    {
        \woocommerce_wp_text_input(self::generate_field($id, $label, 'color', $value, $classes, $description));
    }

    public static function checkbox_input(string $id, string $label, bool $value, array $classes = [], string $description = ''): void
    {
        \woocommerce_wp_checkbox(self::generate_field($id, $label, 'checkbox', $value, $classes, $description));
    }

    private static function generate_field(string $id, string $label, string $type, $value, array $classes, string $description): array
    {
        return array(
            'id' => $id,
            'wrapper_class' => implode(' ', $classes),
            'label' => $label,
            'type' => $type,
            'desc_tip' => !empty($description),
            'description' => $description ?: '',
            'value' => $value,
        );
    }
}
