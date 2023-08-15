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
 * @package           amc-activities-shortcode
 *
 * @wordpress-plugin
 * Plugin Name:       AMC Activities Shortcode
 * Plugin URI:        https://github.com/graybirchsolutions/amc-activities-shortcode
 * Description:       Display events from the AMC Activities Database via shortcode. Events are retrieved from the AMC Activities Database using a simple HTTP query. Activities are formatted and displayed in the page or post as events. <strong>Usage: [amc_activities chapter=id committee=id activity=id limit=n]</strong>. Chapter is the only required parameter, all other parameters are optional. Limit defaults to 10. For additional documentation - including Chapter, Committee and Activity IDs, please see the <a href="https://github.com/graybirchsolutions/amc-activities-shortcode/wiki">AMC Activities Shortcode Project Wiki</a>.
 * Version:           2.0.4
 * Author:            gray birch solutions
 * Author URI:        https://graybirch.solutions/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('AMC_ACTIVITIES_BASENAME', plugin_basename(__FILE__));
define('AMC_ACTIVITIES_ROOT', dirname(plugin_basename(__FILE__)));
define('AMC_ACTIVITIES_FILE', basename(plugin_basename(__FILE__)));
define('AMC_ACTIVITIES_DIR_URL', plugin_dir_url(__FILE__));
define('AMC_ACTIVITIES_DIR_PATH', plugin_dir_path(__FILE__));
define('AMC_ACTIVITIES_PUBLIC_DIR_URL', AMC_ACTIVITIES_DIR_URL . 'public/');
define('AMC_ACTIVITIES_VERSION', '2.0.4');
define('AMC_ACTIVITIES_ASSET_VERSION', '2.0.4');

define('AMC_ACTIVITIES_BASE_URL', 'https://activities.outdoors.org/xml/index.cfm');
define('AMC_ACTIVITIES_BASE_EVENT_URL', 'https://activities.outdoors.org/search/index.cfm/action/details/id/');

define('AMC_API_ROOT', 'AMCActivities/1.0');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/AMCActivitiesClass.php';

/**
 * Activation Hook & Notices
 */
register_activation_hook(__FILE__, 'on_activation');

/**
 * The activation hook.
 * 
 * @since 2.0.3
 * Checks for allow_url_fopen in php settings. Sets transient to trigger
 * an admin notice later.
 * 
 * Note: Don't try to deactivate the plugin in the hook. Appears that 
 * the hook fires before the plugin is actually activated in WP.
 */
function on_activation()
{
    if (ini_get("allow_url_fopen") == "1") {
        set_transient('amc-activation-success', true, 5);
    } else {
        set_transient('amc-activation-error', true, 5);
    }
}

/**
 * Admin notices. Triggered via transient from activation hook.
 * @since 2.0.3
 */
add_action('admin_notices', 'activation_error_msg');
add_action('admin_notices', 'activation_success_msg');

function activation_error_msg()
{
    if (get_transient('amc-activation-error')) {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>
                Error! The AMC Activities Shortcode plugin requires the option <b>allow_url_fopen</b> to be turned <b>On</b> in your host's php.ini settings.
                The plugin has been deactivated.
            </p>
            <p>Please contact your server admin or hosting provider if you are not certain how to modify your site php.ini settings.</p>
        </div>
        <?php
        // Might be a better place to deactivate the plugin on activation error, but this seems to work consistently and avoids more hooks.
        include_once ABSPATH . '/wp-admin/includes/plugin.php';
        deactivate_plugins(plugin_basename(__FILE__));
    }
}

function activation_success_msg()
{
    if (get_transient('amc-activation-success')) {
    ?>
        <div class="notice notice-success is-dismissible">
            <p>
                The AMC Activities Shortcode plugin is ready for action. Please visit the 
                <a href="https://github.com/graybirchsolutions/amc-activities-shortcode/wiki">Project Wiki</a> if you need help getting started.
            </p>
        </div>
<?php
    }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function amc_activities_boot()
{
    $plugin = new \AMCActivities\AMCActivitiesClass();
    $plugin->run();
}

// kick off
amc_activities_boot();
