<?php

declare(strict_types=1);

namespace PWP\includes\menus;

abstract class Admin_Menu implements IWPMenu
{
    public abstract function render_menu(): void;

    final public static function text_property_callback(array $args): void
    {
        $option = $args['option'];
        $value = get_option($option);
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
        $description = isset($args['description']) ? $args['description'] : '';

        $classArray = isset($args['classes']) ? $args['classes'] : [];
        $classArray[] = 'regular-text';
        $classes = implode(" ", $classArray);

        echo "<input id='{$option}' name='{$option}' value='{$value}' placeholder='{$placeholder}' type='text' class='{$classes}' />";
        if ($description) {
            echo "<p class='description'>{$description}</p>";
        }
    }

    final public static function bool_property_callback(array $args): void
    {
        $option = $args['option'];
        $description = $args['description'] ?: '';

        $classArray = isset($args['classes']) ? $args['classes'] : [];
        $classArray[] = 'regular-text';
        $classes = implode(" ", $classArray);

        echo "<input type='checkbox' id='{$option}' name='{$option}' value='1' class='{$classes}' " . checked(1, get_option($option), false) . "/>";
        if ($description) {
            echo "<p class='description'>{$description}</p>";
        }
    }
}
