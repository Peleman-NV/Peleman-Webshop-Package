<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

/**
 * Adds the primary control panel to the Admin Control panel. Other control panels should be children of the one defined here.
 */
class Admin_Control_Panel extends Abstract_Action_Hookable
{
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
            "Peleman_Control_Panel",
            array($this, 'render_panel'),
            'dashicons-hammer',
            120
        );
    }

    public function render_panel(): void
    {
?>
        <div class="wrap pwp_settings">
            <h1> Webshop Settings </h1>
            <hr>
            <div>
                <h2>Buttons & Labels</h2>
                <form method="POST" action="options.php">
                    <?php
                    settings_fields('webshopOptions-group');
                    do_settings_sections('webshopOptions-group');
                    ?>
                    <h3>Archive Labels</h3>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label for="pwp_customize_label"><?php echo __("Simple product - customizable", PWP_TEXT_DOMAIN); ?></label>
                            </th>
                            <td>
                                <input id="pwp_customize_label" name="pwp_customize_label" value="<?php echo get_option('pwp_customize_label'); ?>" placeholder="customize me" type="text" class="regular-text" />
                                <p class="description"><?php echo __("label for products that require customization/user input", PWP_TEXT_DOMAIN); ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="pwp_archive_var_label"><?php echo __("Variable product - Customizable", PWP_TEXT_DOMAIN); ?></label>
                            </th>
                            <td>
                                <input id="pwp_archive_var_label" name="pwp_archive_var_label" value="<?php echo get_option('pwp_archive_var_label'); ?>" placeholder="customize me" type="text" class="regular-text" />
                                <p class="description"><?php echo __("label for customizable variable products", PWP_TEXT_DOMAIN); ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="pwp_cleanup_cutoff_date"><?php echo __("PDF cleanup cutoff date", PWP_TEXT_DOMAIN); ?></label>
                            </th>
                            <td>
                                <input id="pwp_project_cleanup_cutoff_days" name="pwp_project_cleanup_cutoff_days" placeholder="15" value="<?php echo get_option('pwp_project_cleanup_cutoff_days'); ?>" type=number min=0 max=366 />
                                <p class="description"><?php echo __("amount of days before an uploaded pdf project is removed. Only pdfs which have not been ordered will be deleted.", PWP_TEXT_DOMAIN); ?></p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
        </div>
<?php

    }
}
