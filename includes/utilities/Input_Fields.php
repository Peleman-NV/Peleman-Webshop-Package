<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

class Input_Fields
{
    public static function create_field(string $id, string $label, string $type,  $value, array $classes = [], string $description = ''): void
    {
        switch ($type) {
            case ('text'):
                self::text_input($id, $label, (string)$value, '', $classes, $description);
                break;
            case ('number'):
                self::number_input($id, $label, (string)$value, $classes, $description);
                break;
            case ('bool'):
                self::checkbox_input($id, $label, (bool)$value, $classes, $description);
                break;
        }
    }
    public static function text_input(string $id, string $label, string $value, string $placeholder, array $classes = [], string $description = ''): void
    {
        $array = self::generate_field(
            $id,
            $label,
            'text',
            $value,
            $classes,
            __($description, PWP_TEXT_DOMAIN)
        );
        $array['placeholder'] = $placeholder;
        \woocommerce_wp_text_input($array);
    }

    /**
     * Undocumented function
     *
     * @param string $id
     * @param string $label
     * @param int|float $value
     * @param array $classes
     * @param string $description
     * @param array $attributes
     * @return void
     */
    public static function number_input(string $id, string $label, $value, array $classes = [], string $description = '', array $attributes = []): void
    {
        \woocommerce_wp_text_input(array(
            'id'                => $id,
            'label'             => $label,
            'value'             => $value,
            'type'              => 'number',
            'desc_tip'          => !empty($description),
            'description'       => __($description, PWP_TEXT_DOMAIN),
            'wrapper_class'     => implode(' ', $classes),
            'custom_attributes' => $attributes,
        ));
    }

    public static function color_input(string $id, string $label, string $value, array $classes = [], string $description = ''): void
    {
        \woocommerce_wp_text_input(self::generate_field(
            $id,
            $label,
            'color',
            $value,
            $classes,
            __($description, PWP_TEXT_DOMAIN)
        ));
    }

    public static function checkbox_input(string $id, string $label, bool $value, array $classes = [], string $description = ''): void
    {
        \woocommerce_wp_checkbox(array(
            'id'            => $id,
            'label'         => $label,
            'value'         => $value ? 'yes' : 'no',
            'desc_tip'      => !empty($description),
            'description'   => __($description, PWP_TEXT_DOMAIN),
            'wrapper_class' => implode(' ', $classes),
        ));
    }

    private static function generate_field(string $id, string $label, string $type, $value, array $classes, string $description): array
    {
        return array(
            'id'            => $id,
            'wrapper_class' => implode(' ', $classes),
            'label'         => $label,
            'type'          => $type,
            'desc_tip'      => !empty($description),
            'description'   => __($description, PWP_TEXT_DOMAIN),
            'value'         => $value,
        );
    }

    public static function dropdown_input(string $id, string $label, array $options, string $selectedOption, array $classes = [], string $description = ''): void
    {
?>
        <p class="form-field form-row form-row-full">
            <label for="<?php echo $id; ?>"><?php echo $label; ?></label>
            <select style id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="<?php echo implode(' ', $classes); ?> ">
                <?php foreach ($options as $key => $option) : ?>
                    <option value="<?php echo $key; ?>" <?php echo $key === $selectedOption ? "selected" : ''; ?>><?php echo $option; ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($description)) : ?>
                <span><?php echo $description; ?></span>
            <?php endif; ?>
        </p>
<?php

    }
}
