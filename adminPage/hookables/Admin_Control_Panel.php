<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

/**
 * Adds the primary control panel to the Admin Control panel. Other control panels should be children of the one defined here.
 */
class Admin_Control_Panel extends Abstract_Action_Hookable
{
    public const PAGE_SLUG = 'peleman-control-panel';

    public function __construct()
    {
        parent::__construct('admin_menu', 'pwp_add_control_panel', 9);
    }

    public function pwp_add_control_panel(...$args): void
    {
        add_menu_page(
            __("Peleman Webshop Control Panel", PWP_TEXT_DOMAIN),
            "Peleman PWP",
            "manage_options",
            $this::PAGE_SLUG,
            array($this, 'render_tab_buttons'),
            'dashicons-hammer',
            120
        );
    }

    public function render_tab_buttons()
    {
        $get = filter_input_array(INPUT_GET, array('tab' => FILTER_VALIDATE_INT));
        $activeTab = isset($get['tab']) ? $get['tab'] : 1;
?>
        <div class="wrap">
            <div id="icon-themes" class="icon32"></div>
            <h2>Webshop Settings</h2>
            <?php settings_errors();
            ?>
            <h2 class="nav-tab-wrapper">
                <a href="?page=<?php echo $this::PAGE_SLUG; ?>&tab=1" class="nav-tab <?php echo $activeTab == 'multi_order' ? 'nav_tab_active' : ''; ?>">General</a>
                <a href="?page=<?php echo $this::PAGE_SLUG; ?>&tab=2" class="nav-tab <?php echo $activeTab == 'multi_order' ? 'nav_tab_active' : ''; ?>">Buttons</a>
                <a href="?page=<?php echo $this::PAGE_SLUG; ?>&tab=3" class="nav-tab <?php echo $activeTab == 'multi_order' ? 'nav_tab_active' : ''; ?>">Editor</a>
                <a href="?page=<?php echo $this::PAGE_SLUG; ?>&tab=4" class="nav-tab <?php echo $activeTab == 'multi_order' ? 'nav_tab_active' : ''; ?>">Advanced</a>
            </h2>

            <!-- <form method="post" action="options.php"> -->
            <form method="post" action='<?php echo esc_url(add_query_arg('tab', $activeTab, admin_url('options.php'))); ?>'>
                <?php
                switch ($activeTab) {
                    default:
                    case 1:
                        $this->display_general_message();
                        break;
                    case 2:
                        settings_fields('pwp-button-options-group');
                        do_settings_sections($this::PAGE_SLUG);
                        submit_button();
                        break;
                    case 3:
                        settings_fields('pwp-editor-options-group');
                        do_settings_sections($this::PAGE_SLUG);
                        submit_button();
                        break;
                    case 4:
                        settings_fields('pwp-f2d-options-group');
                        do_settings_sections($this::PAGE_SLUG);
                        submit_button();
                        break;
                }
                ?>
            </form>
        </div>
    <?php
    }

    private function display_general_message()
    {
    ?>
        <div class="pwp-settings">
            <h1>Peleman Webshop Package</h1>
            <h3>current version: <?php esc_html_e(PWP_VERSION); ?></h3>
            <hr>
            <p>The Peleman Webshop Package has been designed to work in tandem with the <b>Peleman Image Editor (PIE)</b></p>
            <p>The PWP plugin requires the following plugins for its functionality:</p>
            <ul>
                <li>Woocommerce 7.2.0+</li>
            </ul>
            <hr>
            <p>For proper communication with the <b>PIE</b>, The plugin will require proper PIE API credentials. Please go to the <b>Editor tab</b> to get started.</p>
        </div>
<?php
    }
}
