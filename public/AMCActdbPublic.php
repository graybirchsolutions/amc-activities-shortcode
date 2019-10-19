<?php namespace AMCActdb\FrontEnd;

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
        add_shortcode('amc_actdb', array($this, 'render_activities_shortcode'));
    }

    public function render_activities_shortcode( $atts )
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case( (array)$atts, CASE_LOWER );

        extract( shortcode_atts(
            array(
              'chapter'		 => '2',
              'committee'	 => '',
              'activity'	 => '',
              'display'    => 'short',
              'limit'      => '0'
            ),
            $atts,
            'amc_actdb'
          ) );

        $amc_url = AMC_ACTDB_BASE_URL . '?' . 'chapter=' . esc_attr( $chapter );

        if ( $committee != '' ) {
          $amc_url = $amc_url . '&committee=' . esc_attr( $committee );
        }

        if ( $activity != '' ) {
          $amc_url = $amc_url . '&activity=' . esc_attr( $activity );
        }

        $output = "<p>" . $amc_url . "</p>\n";
        // $output = $output . (string)file_get_contents( $amc_url );

        $xmlstring = file_get_contents( $amc_url );

        $activities = new AMCActivityList ( $xmlstring );
        $output .= $activities->render_list( esc_attr( $display ), esc_attr( $limit ));

        return $output;

      }

}
