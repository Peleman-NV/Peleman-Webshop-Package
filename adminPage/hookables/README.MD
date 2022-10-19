# Admin Hookables
All the PWP plugin admin hookables are to be found here. Each hookable represents a single hookable method, be it a filter, action, ajax call, cron job, or shortcode.

## PWP_Add_Cron_Schedules
adds additional WP Cron schedules for longer timespans.

## PWP_Add_PDF_Contents_To_Cart
OBSOLETE

## PWP_Admin_Control_Panel
Hookable adding the primary control panel for the Peleman Webshop Plugin to the wordpress control panel

## PWP_Admin_Enqueue_Scripts
Hookable for enqueueing Javascript scripts for the Admin control panels. Not the same as Ajax calls.

## PWP_Admin_Enqueue_Styles
Hookable for enqueueing CSS style sheets for the Admin control panels.

## PWP_Cleanup_Unordered_Projects
Helper hookable for cleaning up uploaded PDF and related project entries that have not been ordered after two weeks.

Runs a cron job every day to check for non-ordered projects. if a project has not been ordered, its creation/update age will be checked against the current date.

## PWP_Parent_Custom_Fields
OBSOLETE
hookable intended to add additional fields to Woocommerce products.

## PWP_Parent_Product_Custom_Fields
Adds additional parameter fields to a parent product in Woocommerce. Adds more options to simple products.

## PWP_PIE_Editor_Control_Panel
Adds a control panel to the Wordpress Admin Control Panel for the Peleman Image Editor connection. 

## PWP_Register_Editor_Options
Hookable to read and save any changes to the PIE control panel upon form submission.

## PWP_Register_Plugin_Options
Hookable to read and save any changes to the Plugin control panel upon form submission.

## PWP_Save_Parent_Product_Custom_Fields
Hookable to read and save any changes to the parent product parameters added by the *PWP_Parent_Custom_Fields* hookable. 

## PWP_Save_Variable_Product_Custom_Fields
Hookable to read and save any changes to the parent product parameters added by the *PWP_Variable_Product_Custom_Fields* hookable. 

## PWP_Variable_Product_Custom_Fields
Add additional parameter fields to a *variant* product in woocommerce.