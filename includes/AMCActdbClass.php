<?php

namespace AMCActdb\Classes;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://graybirch.solutions
 * @since      1.0.0
 *
 * @package    AMC_actdb_shortcode
 * @subpackage AMC_actdb_shortcode/includes
 * @author     Martin Jensen <marty@graybirch.solutions>
 */

use AMCActdb\FrontEnd\AMCActdbPublic;

class AMCActdbClass
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      AMCActdbLoader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = 'amc-actdb-shortcode';
        $this->version = AMC_ACTDB_VERSION;
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     * Include the following files that make up the plugin:
     *
     * - AMCActdbLoader. Orchestrates the hooks of the plugin.
     * - Wp_table_data_press_Admin. Defines all hooks for the admin area.
     * - Wp_table_data_press_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/AMCActdbLoader.php';

        /**
         * Include all globally accessible functions.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/AMCActdbGlobal.php';

        /**
         * Include Libs
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/libs/autoload.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         * Back-end functionality.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/AMCActdbAdmin.php';

        /**
         * The classes responsible for defining all actions that occur in the public-facing
         * side of the site. Front-end functionality.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/AMCActdbPublic.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/AMCActivityList.php';

        /*
         * Create the loader.
         */

        $this->loader = new AMCActdbLoader();
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new AMCActdbPublic($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('init', $plugin_public, 'register_activities_render_functions');

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueueAMCActdbScript', 100);

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    AMCActdbLoader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }


    public function addPluginDeactivationMessage()
    {

    }

}
