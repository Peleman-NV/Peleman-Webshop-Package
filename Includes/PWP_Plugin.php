<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\adminPage\hookables\PWP_Add_Cron_Schedules;
use PWP\templates\PWP_Template;
use PWP\includes\loadables\PWP_Plugin_Loader;

use PWP\publicPage\hookables\PWP_Ajax_Add_To_Cart;
use PWP\publicPage\hookables\PWP_Ajax_Show_Variation;
use PWP\publicPage\hookables\PWP_Enqueue_Public_Styles;
use PWP\publicPage\hookables\PWP_Display_PDF_Upload_Form;
use PWP\publicPage\hookables\PWP_Display_PDF_Fields_On_Variations;
use PWP\publicPage\hookables\PWP_Add_Custom_Project_On_Return;
use PWP\publicPage\hookables\PWP_Change_Add_To_Cart_Button_Label;
use PWP\publicPage\hookables\PWP_Display_Editor_Project_Button_In_Cart;
use PWP\publicPage\hookables\PWP_Add_Fields_To_Add_To_Cart_Button;
use PWP\publicPage\hookables\PWP_Change_Add_To_Cart_Label_For_Archive;
use PWP\publicPage\hookables\PWP_Save_Cart_Item_Meta_To_Order_Item_Meta;

use PWP\adminPage\hookables\PWP_Admin_Control_Panel;
use PWP\adminPage\hookables\PWP_Admin_Enqueue_Scripts;
use PWP\adminPage\hookables\PWP_Admin_Notice_Poster;
use PWP\adminPage\hookables\PWP_Admin_Enqueue_Styles;
use PWP\adminPage\hookables\PWP_Cleanup_Unordered_Projects;
use PWP\adminPage\hookables\PWP_Register_Editor_Options;
use PWP\adminPage\hookables\PWP_PIE_Editor_Control_Panel;
use PWP\adminPage\hookables\PWP_Parent_Product_Custom_Fields;
use PWP\adminPage\hookables\PWP_Variable_Product_Custom_Fields;
use PWP\adminPage\hookables\PWP_Save_Parent_Product_Custom_Fields;
use PWP\adminPage\hookables\PWP_Save_Variable_Product_Custom_Fields;

use PWP\includes\API\PWP_API_V1_Plugin;
use PWP\includes\API\endpoints\PWP_TEST_OAuth2_Client_Endpoint;
use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use PWP\publicPage\hookables\PWP_Display_PDF_Data_In_Cart;
use PWP\publicPage\hookables\PWP_Add_PDF_Prices_To_Cart;
use PWP\publicPage\hookables\PWP_Add_PDF_Data_To_Cart_Item;
use PWP\publicPage\hookables\PWP_Ajax_Upload_PDF;
use PWP\publicPage\hookables\PWP_Order_Project;
use PWP\publicPage\hookables\PWP_Remove_PDF_On_Cart_Deletion;
use PWP\publicPage\hookables\PWP_Validate_PDF_Upload;

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

defined('ABSPATH') || exit;

class PWP_Plugin
{
    private PWP_Plugin_Loader $loader;
    private string $version;
    private string $plugin_name;
    private PWP_Admin_Notice_Poster $noticePoster;
    private PWP_Template $templateEngine;

    private function __construct()
    {
        $this->version = defined('PWP_VERSION') ? PWP_VERSION : '1.0.0';
        $this->plugin_name = 'Peleman Webshop Package';
        $this->loader = new PWP_Plugin_Loader();
        $this->noticePoster = new PWP_Admin_Notice_Poster();
        $this->templateEngine = new PWP_Template(PWP_TEMPLATES_DIR);

        if (!$this->check_if_requirements_met()) {
            return;
        }

        if (is_admin()) {
            $this->admin_hooks();
        }
        $this->public_hooks();
        $this->api_endpoints();
    }

    final public static function run()
    {
        $instance = new PWP_Plugin();
        $instance->register_components();
        do_action('pwp_plugin_loaded');
    }
    private function admin_hooks(): void
    {
        $this->add_hookable($this->noticePoster);
        /** control panel hookables */
        $this->add_hookable(new PWP_Admin_Enqueue_Styles());
        $this->add_hookable(new PWP_Register_Editor_Options());
        $this->add_hookable(new PWP_PIE_Editor_Control_Panel());
        $this->add_hookable(new PWP_Admin_Control_Panel());

        /* product page hookables */
        $this->add_hookable(new PWP_Parent_Product_Custom_Fields());
        $this->add_hookable(new PWP_Variable_Product_Custom_Fields());
        $this->add_hookable(new PWP_Save_Parent_Product_Custom_Fields());
        $this->add_hookable(new PWP_Save_Variable_Product_Custom_Fields());

        /** cron jobs */
        $this->add_hookable(new PWP_Add_Cron_Schedules());
        $this->add_hookable(new PWP_Cleanup_Unordered_Projects());
    }

    private function public_hooks(): void
    {
        $this->add_hookable(new PWP_Enqueue_Public_Styles());
        $this->add_hookable(new PWP_Change_Add_To_Cart_Label_For_Archive());
        $this->add_hookable(new PWP_Change_Add_To_Cart_Button_Label());
        $this->add_hookable(new PWP_Add_Fields_To_Add_To_Cart_Button());

        /* PDF upload hookables */
        // $this->add_hookable(new PWP_Display_PDF_Fields_On_Variations());
        $this->add_hookable(new PWP_Display_PDF_Upload_Form($this->templateEngine));
        $this->add_hookable(new PWP_Validate_PDF_Upload);
        $this->add_hookable(new PWP_Add_PDF_Data_To_Cart_Item());
        $this->add_hookable(new PWP_Display_PDF_Data_In_Cart());
        $this->add_hookable(new PWP_Remove_PDF_On_Cart_Deletion());
        $this->add_hookable(new PWP_Add_PDF_Prices_To_Cart());
        $this->add_hookable(new PWP_Order_Project());

        /* EDITOR product hookables */
        $this->add_hookable(new PWP_Ajax_Show_Variation());
        $this->add_hookable(new PWP_Ajax_Add_To_Cart());
        $this->add_hookable(new PWP_Display_Editor_Project_Button_In_Cart());
        $this->add_hookable(new PWP_Add_Custom_Project_On_Return());
        $this->add_hookable(new PWP_Save_Cart_Item_Meta_To_Order_Item_Meta());
    }

    private function api_endpoints(): void
    {
        $this->loader->add_API_endpoint(new PWP_TEST_OAuth2_Client_Endpoint());

        $this->add_hookable(new PWP_API_V1_Plugin('pwp/v1'));
    }

    private function register_components()
    {
        $this->loader->register_hooks_to_wp();
    }

    private function check_if_requirements_met(): bool
    {
        if (!\is_plugin_active('woocommerce/woocommerce.php')) {
            $this->noticePoster->new_warning_notice("{$this->plugin_name} needs Woocommerce to function properly!", true);
        }

        if (!\is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
            $this->noticePoster->new_error_notice("{$this->plugin_name} needs WPML to function properly", true);
        }

        return true;
    }

    private function add_hookable(PWP_I_Hookable_Component $hookable): void
    {
        $this->loader->add_hookable(($hookable));
    }
}
