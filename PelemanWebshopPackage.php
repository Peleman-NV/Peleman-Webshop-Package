<?php

declare(strict_types=1);

namespace PWP;

require plugin_dir_path(__FILE__) . '/vendor/autoload.php';

use PWP\includes\Activator;
use PWP\includes\Deactivator;
use PWP\includes\Plugin;

/**
 * @link              https://www.peleman.com
 * @since             0.1.0
 * @package           PWP
 *
 * @wordpress-plugin
 * Plugin Name:       Peleman - Peleman Webshop Package
 * Plugin URI:        https://www.peleman.com
 * requires PHP:      7.4
 * requires at least: 5.9.0
 * Description:       In-development umbrella project of the Peleman Product Uploader and Print Partner Integrator.
 * Version:           1.6.6
 * Author:            Peleman NV, Kai Helsen
 * Author URI:        https://github.com/KaiHelsen
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       'Peleman-Webshop-Package'
 * Domain Path:       /languages
 */

defined('WPINC') || die;

//define PWP constant values
define('PWP_VERSION', '1.6.6');
!defined('PWP_OPTION_GROUP')        ? define('PWP_OPTION_GROUP', 'OPTIONS') : null;

!defined('PWP_DIRECTORY')           ? define('PWP_DIRECTORY', plugin_dir_path(__FILE__)) : null;
/**@phpstan-ignore-next-line */
!defined('PWP_UPLOAD_DIR')          ? define('PWP_UPLOAD_DIR', WP_CONTENT_DIR . '/uploads/pwp/') : null;
!defined('PWP_TEMPLATES_DIR')       ? define('PWP_TEMPLATES_DIR', plugin_dir_path(__FILE__) . '/templates') : null;
/**@phpstan-ignore-next-line */
!defined('PWP_LOG_DIR')             ? define('PWP_LOG_DIR', WP_CONTENT_DIR . '/uploads/pwp/logs') : null;

!defined('PWP_API_KEY_TABLE')       ? define('PWP_API_KEY_TABLE', 'pwp_api_keys') : null;
!defined('PWP_PROJECTS_TABLE')      ? define('PWP_PROJECTS_TABLE', 'pwp_projects') : null;


//register activation hook. Is called when the plugin is activated in the Wordpress Admin panel
register_activation_hook(__FILE__, function () {
    $activator = new Activator();
    $activator->activate();
});

//register deactivation hook. Is called when the plugin is deactivated in the Wordpress Admin panel
register_deactivation_hook(__FILE__, function () {
    $deactivator = new Deactivator();
    $deactivator->deactivate();
});

Plugin::run();
