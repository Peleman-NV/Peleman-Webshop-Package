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
    private const PWP_API_KEY_TABLE = 'pwp_api_keys';

    public function __construct()
    {
    }
    public function activate()
    {
        $this->init_settings();
        $this->init_database_tables();
        $this->run_upgrades();
    }

    public function init_settings()
    {
        register_setting(PWP_OPTION_GROUP, 'pwp-version', array(
            'default' => '0.0.1',
        ));
    }
    public function init_database_tables()
    {
        global $wpdb;
        if ($wpdb instanceof wpdb) {
            $table_name = $wpdb->prefix . self::PWP_API_KEY_TABLE;

            $charset_collate = $wpdb->get_charset_collate();

            //create table to store API keys
            \dbDelta($wpdb->prepare(
                "CREATE TABLE %s (
            id              mediumint(9) NOT NULL AUTO_INCREMENT,
            name            tinytext DEFAULT NULL,
            key             tinytext NOT NULL,
            hashed_secret   tinytext NOT NULL,
            key_suffix      tinytext NOT NULL,
            salt            int(11) NOT NULL,
            created         datetime DEFAULT CURRENT_TIMESTAMP,
            modified        datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
            rate_limit      decimal(10,2) DEFAULT NULL,

            PRIMARY KEY (id)) %s",
                array(
                    $table_name,
                    $charset_collate
                )
            ));
        }
    }

    public function run_upgrades()
    {
        $versionController = new PWP_VersionController(PWP_VERSION, get_option('pwp-version'));
        $versionController->try_update();
    }
}
