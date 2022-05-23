<?php

declare(strict_types=1);

namespace PWP;

require plugin_dir_path(__FILE__) . '/vendor/autoload.php';

use PWP\includes\PWP_Activator;
use PWP\includes\PWP_Deactivator;
use PWP\includes\PWP_Plugin;
use PWP\includes\versionControl\PWP_VersionController;

/**
 * @link              https://www.peleman.com
 * @since             0.1.0
 * @package           PWP
 *
 * @wordpress-plugin
 * Plugin Name:       Peleman Webshop Package
 * Plugin URI:        https://www.peleman.com
 * requires PHP:      7.4
 * requires at least: 5.9.0
 * Description:       In-development umbrella project of the Peleman Product Uploader and Print Partner Integrator.
 * Version:           0.3.4
 * Author:            Kai Helsen
 * Author URI:        https://github.com/KaiHelsen
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       PelemanWebshopPackage
 * Domain Path:       /languages
 */

defined('WPINC') || die;

define('PWP_VERSION', '0.3.4');
!defined('PWP_OPTION_GROUP') ? define('PWP_OPTION_GROUP', 'PWP_OPTIONS') : null;
!defined('PWP_TEXT_DOMAIN') ? define('PWP_TEXT_DOMAIN', 'PelemanWebshopPackage') : null;
!defined('PWP_THUMBNAIL_DIR') ? define('PWP_THUMBNAIL_DIR', WP_CONTENT_DIR . '/uploads/pwp/thumbnails') : null;

//register activation hook. Is called when the plugin is activated in the Wordpress Admin panel
register_activation_hook(__FILE__, function () {
    $activator = new PWP_Activator();
    $activator->activate();

    $versionController = new PWP_VersionController(PWP_VERSION, (string)get_option('pwp-version'));
    $versionController->try_update();
});

//register deactivation hook. Is called when the plugin is deactivated in the Wordpress Admin panel
register_deactivation_hook(__FILE__, function () {
    $deactivator = new PWP_Deactivator();
    $deactivator->deactivate();
});

PWP_Plugin::run();
