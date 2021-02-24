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
 * @package    AMC_activities_shortcode
 * @subpackage AMC_activities_shortcode/public
 * @author     Martin Jensen <marty@graybirch.solutions>
 */

namespace AMCActivities\FrontEnd;

use AMCActivities\api\v1\Boot;

class AMCActivitiesPublic
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
        $activities_route = new \AMCActivities\api\v1\routes\Activities();
        $plugin_api = new \AMCActivities\api\v1\Boot(AMC_API_ROOT);

        $plugin_api->addRoute($activities_route);
        $plugin_api->registerAllRoutes();
    }

    public function get_activities($chapter, $committee, $activity)
    {
        $amc_url = AMC_ACTIVITIES_BASE_URL . '?' . 'chapter=' . esc_attr($chapter);

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
                'limit'      => '0'
            ),
            $atts,
            'amc_activities'
        ));

        $activities = new AMCActivityList($this->get_activities($chapter, $committee, $activity));

        // 2.0.0 Shortcode now renders a placeholder in the DOM that will be filled in by Javascript in the browser

        return $activities->render_placeholder(esc_attr($chapter), esc_attr($committee), esc_attr($activity), esc_attr($limit));
    }

    public function enqueueAMCActivitiesScript()
    {
        global $post;
        if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'amc_activities') ) {
            $styleSrc = AMC_ACTIVITIES_DIR_URL . "assets/css/AMCActivitiesPublic.css";
            wp_enqueue_style(
                'amc-activities-styles',
                $styleSrc,
                array(),
                $this->version,
                'all'
            );

            // Add Font Awesome kit
            // <script src="https://kit.fontawesome.com/db96351575.js" crossorigin="anonymous"></script>

            $styleSrc = AMC_ACTIVITIES_DIR_URL . "vendor/components/font-awesome/css/solid.min.css";
            wp_enqueue_style(
                'amc-activities-awesome',
                $styleSrc,
                array(),
                $this->version,
                'all'
            );

            $scriptSrc = AMC_ACTIVITIES_DIR_URL . "assets/js/amcActivitiesRenderEvents.js";
            wp_enqueue_script(
                'amc-activities-render-events',
                $scriptSrc,
                array(),
                $this->version,
                true
            );
        }
    }

}
