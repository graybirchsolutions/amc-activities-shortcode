<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://graybirch.solutions
 * @since             1.0.0
 *
 * @package           amc-actdb-shortcode
 *
 * @wordpress-plugin
 * Plugin Name:       AMC Activities Shortcode
 * Plugin URI:        https://github.com/graybirchsolutions/amc-actdb-shortcode
 * Description:       Display events from the AMC Activities Database via shortcode. Events are retrieved from the AMC Activities Database using a simple HTTP query. Activities are formatted and displayed in the page or post as events. <strong>Usage: [amc_activities chapter=id committee=id activity=id limit=n]</strong>. Chapter is the only required parameter, all other parameters are optional. Limit defaults to 10. For additional documentation - including Chapter, Committee and Activity IDs, please see the <a href="https://github.com/graybirchsolutions/amc-actdb-shortcode/wiki">AMC Activities Shortcode Project Wiki</a>.
 * Version:           2.0.0
 * Author:            gray birch solutions
 * Author URI:        https://graybirch.solutions/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('AMC_ACTDB_BASENAME', plugin_basename(__FILE__));
define('AMC_ACTDB_ROOT', dirname(plugin_basename(__FILE__)));
define('AMC_ACTDB_FILE', basename(plugin_basename(__FILE__)));
define('AMC_ACTDB_DIR_URL', plugin_dir_url(__FILE__));
define('AMC_ACTDB_DIR_PATH', plugin_dir_path(__FILE__));
define('AMC_ACTDB_PUBLIC_DIR_URL', AMC_ACTDB_DIR_URL . 'public/');
define('AMC_ACTDB_VERSION', '2.0.0');
define('AMC_ACTDB_ASSET_VERSION', '2.0.0');

define('AMC_ACTDB_BASE_URL', 'https://activities.outdoors.org/xml/index.cfm');
define('AMC_ACTDB_BASE_EVENT_URL', 'https://activities.outdoors.org/search/index.cfm/action/details/id/');

define('AMC_API_ROOT', 'AMCActdb/1.0');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/AMCActdbClass.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function amc_actdb_boot()
{
    $plugin = new \AMCActdb\AMCActdbClass();
    $plugin->run();
}

// kick off
amc_actdb_boot();
