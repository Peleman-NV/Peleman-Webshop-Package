<?php

declare(strict_types=1);

namespace PPA;

require plugin_dir_path(__FILE__) . '/vendor/autoload.php';

use PPA\includes\PPA_Activator;
use PPA\includes\PPA_Deactivator;
use PPA\includes\PPA_plugin;

/**
 * @link              https://www.peleman.com
 * @since             1.0.0
 * @package           PPA
 *
 * @wordpress-plugin
 * Plugin Name:       Peleman Product API
 * Plugin URI:        https://www.peleman.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Kai Helsen
 * Author URI:        https://github.com/KaiHelsen
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       PPA
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

define('PPA_VERSION', '1.0.0');

register_activation_hook(__FILE__, function () {
    PPA_Activator::activate();
});
register_deactivation_hook(__FILE__, function () {
    PPA_Deactivator::deactivate();
});

function run_plugin_name()
{
    $plugin = new PPA_Plugin();
    $plugin->run();
}
run_plugin_name();
