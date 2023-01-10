<?php

declare(strict_types=1);

namespace PWP\includes\menus;

abstract class Admin_menu implements IWPMenu
{
    public abstract function render_menu(): void;

    final public static function text_property_callback($args): void
    {
        $option = $args['option'];
        $value = get_option($option);
        $placeholder = $args['placeholder'] ?: '';
        $description = $args['description'] ?: '';

        $classArray = $args['classes'];
        $classArray[] = 'regular-text';
        $classes = implode(" ", $classArray);

        echo "<input id='{$option}' name='{$option}' value='{$value}' placeholder='{$placeholder}' type='text' class='{$classes}' />";
        if ($description) {
            echo "<p class='description'>{$description}</p>";
        }
    }
}
