<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;

class PWP_PIE_Editor_Control_Panel extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('admin_menu', 'pwp_register_control_panel');
    }
    public function pwp_register_control_panel(): void
    {
        add_submenu_page(
            "Peleman_Control_Panel",
            "Editor Settings",
            "Editor Settings",
            "manage_options",
            "PWP_Editor_Config",
            array($this, "render_menu"),
            1
        );
    }

    public function render_menu(): void
    {
?>
        <div class="wrap pwp_settings">
            <h1>Editor Settings</h1>
            <hr>
            <div>
                <p>
                    the Peleman Webshop Package has been designed to work in tandem with the Peleman Image Editor (PIE)
                    In order for proper communication with the PIE, they have to be set up to communicate with one another
                </p>
                <p>
                    in this panel, you can enter this webshop's credentials for accessing the PIE API.
                </p>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Proin lacinia erat eget risus congue, tincidunt rutrum ligula pharetra.
                    Proin malesuada velit ipsum, sit amet tempor risus molestie et.
                    Aenean euismod libero vitae turpis interdum bibendum.
                    Donec ac convallis ante. Sed ut scelerisque lacus.
                    Maecenas non faucibus leo.
                    Vestibulum tempus, ex vel venenatis semper, nisi mauris vestibulum lacus, et varius quam purus sit amet nunc.
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eget ligula vel nulla malesuada rhoncus id et risus.
                </p>
            </div>
            <form method="POST" action="options.php">
                <?php
                settings_fields('editorOptions-group');
                do_settings_sections('editorOptions-group');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <label for="pie_domain">PIE Domain (URL)</label>
                        </th>
                        <td>
                            <input id="pie_domain" name="pie_domain" type="text" value="<?= get_option('pie_domain') ?>" placeholder="https://deveditor.peleman.com" class="regular-text code" />
                            <p class="description" id="tagline-description">base Site Address of the PIE editor</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="pie_customer_id">PIE Customer ID</label>
                        </th>
                        <td>
                            <input id=" pie_customer_id" name="pie_customer_id" type="text" value="<?= get_option('pie_customer_id'); ?>" class="regular-text" />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <label for="pie_api_key">PIE api key</label>
                        </th>
                        <td>
                            <input id="pie_api_key" name="pie_api_key" type="text" value="<?= get_option('pie_api_key'); ?>" class="regular-text" />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <label for="pwp_imaxel_private_key">IMAXEL Private Key</label>
                        </th>
                        <td>
                            <input id=" pwp_imaxel_private_key" name="pwp_imaxel_private_key" type="text" value="<?= get_option('pwp_imaxel_private_key'); ?>" class="regular-text" />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <label for="pwp_imaxel_public_key">IMAXEL Public Key</label>
                        </th>
                        <td>
                            <input id="pwp_imaxel_public_key" name="pwp_imaxel_public_key" type="text" value="<?= get_option('pwp_imaxel_public_key'); ?>" class="regular-text" />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <label for="pwp_imaxel_shop_code">IMAXEL Shop Code</label>
                        </th>
                        <td>
                            <input id="pwp_imaxel_shop_code" name="pwp_imaxel_shop_code" type="text" value="<?= get_option('pwp_imaxel_shop_code'); ?>" class="regular-text" />
                        </td>
                    </tr>

                </table>
                <?php submit_button(); ?>
            </form>
        </div>
<?php
    }
}
