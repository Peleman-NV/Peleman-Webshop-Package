<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\versionControl\PWP_VersionController;
use wpdb;

defined('ABSPATH') || exit;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
/**
 * activator class for the Peleman Product API plugin
 * is to be run when the plugin is activated from the Wordpress admin menu
 */
class PWP_Activator
{
    public function __construct()
    {
    }
    public function activate()
    {
        $this->init_settings();
        $this->init_database_tables();
        $this->init_directories();
        $this->run_upgrades();
    }

    public function init_settings()
    {
        register_setting(PWP_OPTION_GROUP, 'pwp-version', array(
            'default' => '0.0.1',
        ));

        register_setting(PWP_OPTION_GROUP, 'pwp_project_cleanup_cutoff_days', array(
            'type' => 'integer',
            'default' => 15,
            'description' => 'if a customer has a project or PDF that is not ordered, how many days before we delete the local files?',
        ));
    }
    public function init_database_tables()
    {
        global $wpdb;
        if ($wpdb instanceof wpdb) {
            // $table_name = $wpdb->prefix . PWP_API_KEY_TABLE;

            $charset_collate = $wpdb->get_charset_collate();

            // //create table to store API keys
            // \maybe_create_table($table_name, $wpdb->prepare(
            //     "CREATE TABLE  {$table_name} (
            // id              mediumint(9) NOT NULL AUTO_INCREMENT,
            // name            tinytext DEFAULT NULL,
            // key             tinytext NOT NULL,
            // hashed_secret   tinytext NOT NULL,
            // key_suffix      tinytext NOT NULL,
            // salt            int(11) NOT NULL,
            // created         datetime DEFAULT CURRENT_TIMESTAMP,
            // modified        datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
            // rate_limit      decimal(10,2) DEFAULT NULL,

            // PRIMARY KEY (id)) %s",
            //     array(
            //         $charset_collate
            //     )
            // ));

            $table_name = $wpdb->prefix . PWP_PROJECTS_TABLE;

            $sql = "CREATE TABLE {$table_name} (
                id              mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id         int(11) NOT NULL,
                project_id      tinytext DEFAULT NULL,
                product_id      int(11) NOT NULL,
                file_name       tinytext NOT NULL,
                path            tinytext NOT NULL,
                pages           int(11) DEFAULT NULL,
                price_vat_excl  decimal(15,5) DEFAULT NULL,
                created         datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                updated         datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
                ordered         datetime DEFAULT NULL,
                PRIMARY KEY  (id)
                ){$charset_collate}";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public function run_upgrades()
    {
        $versionController = new PWP_VersionController(PWP_VERSION, get_option('pwp-version'));
        $versionController->try_update();
    }

    public function init_directories()
    {
        if (!is_dir(PWP_UPLOAD_DIR)) {
            mkdir(PWP_UPLOAD_DIR, 0755, true);
        }
    }
}
