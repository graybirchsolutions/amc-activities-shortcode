<?php

/**
 * The admin (back end) functions of this plugin.
 *
 * @link       https://graybirch.solutions
 * @since      1.0.0
 *
 * @package    AMC_activities_shortcode
 * @subpackage AMC_activities_shortcode/admin
 * @author     Martin Jensen <marty@graybirch.solutions>
 */

namespace AMCActivities\Admin;

use WPGitHubUpdater;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class AMCActivitiesAdmin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function github_updater_init() {
        if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin

            define( 'WP_GITHUB_FORCE_UPDATE', false );
            $config = array(
                'slug' => AMC_ACTIVITIES_BASENAME,
                'proper_folder_name' => AMC_ACTIVITIES_ROOT,
                'api_url' => 'https://api.github.com/repos/graybirchsolutions/amc-activities-shortcode',
                'raw_url' => 'https://raw.githubusercontent.com/graybirchsolutions/amc-activities-shortcode/master',
                'github_url' => 'https://github.com/graybirchsolutions/amc-activities-shortcode',
                'zip_url' => 'https://github.com/graybirchsolutions/amc-activities-shortcode/archive/master.zip',
                'sslverify' => true,
                'requires' => '5.1',
                'tested' => '5.6.1',
                'readme' => 'README.md',
                'access_token' => '',
            );
    
            new WPGitHubUpdater( $config );    
        }
    }
}
