<?php

declare(strict_types=1);

namespace PWP;

require plugin_dir_path(__FILE__) . '/vendor/autoload.php';

use PWP\includes\PWP_Activator;
use PWP\includes\PWP_Deactivator;
use PWP\includes\PWP_plugin;

/**
 * @link              https://www.peleman.com
 * @since             1.0.0
 * @package           PWP
 *
 * @wordpress-plugin
 * Plugin Name:       Peleman Webshop Package
 * Plugin URI:        https://www.peleman.com
 * Description:       In-development merger project of the Peleman Product Uploader and Print Partner Integrator.
 * Version:           1.0.0
 * Author:            Kai Helsen
 * Author URI:        https://github.com/KaiHelsen
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       PWP
 * Domain Path:       /languages
 */

defined('WPINC') || die;

define('PWP_VERSION', '1.0.0');

//register activation hook. Is called when the plugin is activated in the Wordpress Admin panel
register_activation_hook(__FILE__, function () {
    PWP_Activator::activate();
});

//register deactivation hook. Is called when the plugin is deactivated in the Wordpress Admin panel
register_deactivation_hook(__FILE__, function () {
    PWP_Deactivator::deactivate();
});

$plugin = new PWP_Plugin();
$plugin->run();
