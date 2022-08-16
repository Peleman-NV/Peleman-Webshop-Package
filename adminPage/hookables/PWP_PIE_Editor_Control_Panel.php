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
                <table class="form-table">
                    <tr valign="top">
                    <tr valign="top">
                        <th scope="row">
                            <label for="pwp_pie_url">PIE Domain (URL)</label>
                        </th>
                        <td>
                            <input id="pwp_pie_url" name="pwp_pie_url" type="text" value="" placeholder="https://deveditor.peleman.com" class="regular-text code" />
                            <p class="description" id="tagline-description">base Site Address of the PIE editor</p>
                        </td>
                    </tr>
                    <th scope="row">
                        <label for="pwp_pie_customer_id">PIE Customer ID</label>
                    </th>
                    <td>
                        <input id="pwp_pie_customer_id" name="pwp_pie_customer_id" type="text" value="" lass="regular-text" />
                    </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <label for="pwp_pie_api_key">PIE api key</label>
                        </th>
                        <td>
                            <input id="pwp_pie_api_key" name="pwp_pie_api_key" type="text" value="" class="regular-text" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <td>
                            <button type="submit" class="button button-primary"> Save Changes</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
<?php
    }
}
