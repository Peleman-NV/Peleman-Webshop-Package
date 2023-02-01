<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;
use PWP\includes\menus\Button_Submenu;
use PWP\includes\menus\Editor_Submenu;
use PWP\includes\menus\F2D_Menu;

class Admin_Submenu_Fields extends Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('admin_init', 'register_submenu_fields');
    }

    public function register_submenu_fields()
    {
        $get = filter_input_array(INPUT_GET, array('tab' => FILTER_VALIDATE_INT));
        $activeTab = isset($get['tab']) ? (int)$get['tab'] : 1;
        switch ($activeTab) {
            default:
            case 1:
                break;
            case 2:
                $menu = new Button_Submenu();
                $menu->render_menu();
                break;
            case 3:
                $menu = new Editor_Submenu();
                $menu->render_menu();
                break;
            case 4:
                $menu = new F2D_Menu();
                $menu->render_menu();
                break;
        }
    }
}
