<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://graybirch.solutions
 * @since      1.0.0
 *
 * @package    AMC_actdb_shortcode
 * @subpackage AMC_actdb_shortcode/public
 * @author     Martin Jensen <marty@graybirch.solutions>
 */

namespace AMCActdb\FrontEnd;

use AMCActdb\api\v1\Boot;

class AMCActdbPublic
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

    public function register_activities_render_functions()
    {
        // register the shortcode
        add_shortcode('amc_activities', array($this, 'render_activities_shortcode'));
    }

    public function registerAPIRoutes()
    {
        $activities_route = new \AMCActdb\api\v1\routes\Activities();
        $plugin_api = new \AMCActdb\api\v1\Boot(AMC_API_ROOT);

        $plugin_api->addRoute($activities_route);
        $plugin_api->registerAllRoutes();
    }

    public function get_activities($chapter, $committee, $activity)
    {
        $amc_url = AMC_ACTDB_BASE_URL . '?' . 'chapter=' . esc_attr($chapter);

        if ($committee != '') {
            $amc_url = $amc_url . '&committee=' . esc_attr($committee);
        }

        if ($activity != '') {
            $amc_url = $amc_url . '&activity=' . esc_attr($activity);
        }

        $xmlstring = file_get_contents($amc_url);

        return $xmlstring;
    }

    public function render_activities_shortcode($atts)
    {
            // normalize attribute keys, lowercase
            $atts = array_change_key_case((array)$atts, CASE_LOWER);

            extract(shortcode_atts(
            array(
                'chapter'         => '2',
                'committee'     => '',
                'activity'     => '',
                'display'    => 'short',
                'limit'      => '0'
            ),
            $atts,
            'amc_actdb'
        ));

        $activities = new AMCActivityList($this->get_activities($chapter, $committee, $activity));

        // 1.1.0 Shortcode now renders a placeholder in the DOM that will be filled in by Javascript in the browser

        return $activities->render_placeholder(esc_attr($chapter), esc_attr($committee), esc_attr($activity), esc_attr($limit), esc_attr($display));
    }

    public function enqueueAMCActdbScript()
    {
        global $post;
        if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'amc_activities') ) {
            $styleSrc = AMC_ACTDB_DIR_URL . "assets/css/amcactdb-public.css";
            wp_enqueue_style(
                'amcactdb-styles',
                $styleSrc,
                array(),
                $this->version,
                'all'
            );

            // Add Font Awesome kit
            // <script src="https://kit.fontawesome.com/db96351575.js" crossorigin="anonymous"></script>

            $styleSrc = AMC_ACTDB_DIR_URL . "vendor/components/font-awesome/css/solid.min.css";
            wp_enqueue_style(
                'amc-actdb-font-awesome',
                $styleSrc,
                array(),
                $this->version,
                'all'
            );

            $scriptSrc = AMC_ACTDB_DIR_URL . "assets/js/amcactdbRenderEvents.js";
            wp_enqueue_script(
                'amc-actdb-render-events',
                $scriptSrc,
                array(),
                $this->version,
                true
            );
        }
    }

}
