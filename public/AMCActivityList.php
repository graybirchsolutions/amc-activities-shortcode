<?php namespace AMCActdb\FrontEnd;

/**
 * Class to render an XML list of AMC trips as HTML
 *
 * @link       https://graybirch.solutions
 * @since      1.0.0
 *
 * @package    AMC_actdb_shortcode
 * @subpackage AMC_actdb_shortcode/public
 * @author     Martin Jensen <marty@graybirch.solutions>
 */

class AMCActivityList
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      SimpleXMLElement $amc_activities - A list of activities in XML format.
   */
  private $amc_activities;

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string $html_string - HTML formatted version of the activities list.
   */
  private $html_string;


  public function __construct( $string )
  {
      $this->amc_activities = new \SimpleXMLElement( $string );
      $this->html_string = '';
  }

  public function render_list ( $display, $limit )
  {

    // Wrap the event list
    $this->html_string .= "<div class=\"amc-events-container\">\n";

    if ( $this->amc_activities->getName() == 'errors' ) {
        $this->html_string = <<<'EOD'
<div class="amc-events-container">
  <div class="amc-event-wrap">
    <div class="amc-event-title-block">
      <div class="amc-event-title">Sorry!</div>
      <div class="amc-event-description">
        No upcoming events are listed in the AMC Activities Calendar. Please
        check back frequently as we're often adding new trips and events to
        the calendar.
      </div>
    </div>
  </div>
</div>
EOD;
        // Close the event list wrap
        $this->html_string .= "</div>\n";
        return $this->html_string;
    }

    $i = 1;
    foreach ( $this->amc_activities->trip as $event ) {
//      $this->html_string .= "<p>" . (string)$amctrip->trip_title . "</p>\n";

      if ( $display == 'short' ) {
        $this->render_event_short ( $event );
      } elseif ( $display == 'long') {
        $this->render_event_long ( $event );
      } else {
        $this->html_string .= "Invalid format: $display\n";
        return $this->html_string;
      }

      if ( $i++ == (int)$limit ) break;
    }

    // Close the event list wrap
    $this->html_string .= "</div>\n";

    return $this->html_string;
  }

  private function render_event_short ( $event )
  {
      // Open event wrap with .amc-event-wrap
      $this->html_string .= "  <div class=\"amc-event-wrap amc-event-short\">\n";

      // Render the event

      // Render date block
      $this->html_string .= $this->render_date_block ( strtotime( $event->trip_start_date ) );

      // Open title block wrap with .amc-event-title-block
      $this->html_string .= "<div class=\"amc-event-title-block\">\n";

      // Render the event title with a link back to the event on the AMC website
      $this->html_string .= "<span class=\"amc-event-title\">" .
          "<a href=\"https://activities.outdoors.org/search/index.cfm/action/details/id/" .
          (string)$event->trip_id . "\">" .
          (string)$event->trip_title . "</a></span>\n";

      $this->html_string .= $this->render_detail_block ( $event );

      // Close title block wrap (.amc-event-title-block)
      $this->html_string .= "</div>\n";

      // Close event wrap (.amc-event-wrap)
      $this->html_string .= "</div>\n";

      return;
  }

  private function render_event_long ( $event )
  {
    // Open event wrap with .amc-event-wrap
    $this->html_string .= "  <div class=\"amc-event-wrap amc-event-long\">\n";

    // Render the event

    // Render date block
    $this->html_string .= $this->render_date_block ( strtotime( $event->trip_start_date ) );

    // Open title block wrap with .amc-event-title-block
    $this->html_string .= "<div class=\"amc-event-title-block\">\n";

    // Render the event title with a link back to the event on the AMC website
    $this->html_string .= "<span class=\"amc-event-title\">" .
        "<a href=\"https://activities.outdoors.org/search/index.cfm/action/details/id/" .
        (string)$event->trip_id . "\">" .
        (string)$event->trip_title . "</a></span>\n";

    $this->html_string .= $this->render_detail_block ( $event );

    // Close title block wrap (.amc-event-title-block)
    $this->html_string .= "</div>\n";

    // Render the event description
    $this->html_string .= "<div class=\"amc-event-description\">\n";
    $this->html_string .= "<h3>Description</h3>\n";
    $this->html_string .= "<div class=\"amc-inner\">\n<p>" .
        str_replace("\n", "</p><p>", $event->web_desc) . "</p>\n</div>\n</div>\n";

    // Close event wrap (.amc-event-wrap)
    $this->html_string .= "</div>\n";

    return;

  }

  /**
   * Function to render the date block from the event date
   *
   * @since    1.0.0
   * @access   private
   * @var      Timestamp $evdate - Unix Timestamp with the event date.
   *
   */

   private function render_date_block ( $evdate ) {

     // Open date block wrap
     $dbstring = "<div class=\"amc-date-block\">\n";

     // Open start date block
     $dbstring .= "  <span class=\"amc-start-date\">\n";

     // Render the date
     $dbstring .= "    <span class=\"date\">" . date("d", $evdate) . "</span>\n";
     $dbstring .= "    <span class=\"month\">" . date("M", $evdate) . "</span>\n";
     $dbstring .= "    <span class=\"year\">" . date("Y", $evdate) . "</span>\n";

     // Close start date
     $dbstring .= "  </span>\n";

     // Close date block
     $dbstring .= "</div>\n";

     return $dbstring;
   }

   private function render_detail_block ( $event ) {

     $event_date = strtotime( $event->trip_start_date );

    // Open detail block wrap with .amc-event-detail-block
    $info_string = "<div class=\"amc-event-detail-block\">";

    // Render date. If time 0000 (midnight) then ignore time else display time
    $info_string .= "<span class=\"amc-event-date\">" . date("D M j Y", $event_date);
    if ( date("Hi", $event_date) != "0000" ) {
      $info_string .= date(', \a\t g:i a', $event_date);
    }
    $info_string .= "</span>\n";

    // Render the event type and event level (if present)
    $i = 0;
    foreach ( $event->activities->activity as $type ) {
      $info_string .= "<span class=\"amc-event-type\"><span class=\"key\">Activity</span>: " . $type . " ";
      if ($i++ == 0 && $event->tripDifficulty != '') {
          $info_string .= "<span class=\"amc-event-level\">(<span class=\"key\">Level</span>: " . $event->tripDifficulty . ")</span>";
      }
      $info_string .= "</span>\n";
    }

    // Render the leader information. Include email if present.
    $info_string .= "<span class=\"amc-event-leader\"><span class=\"key\">Leader</span>: " . (string)$event->leader1;
    if ( (string)$event->leader1_email != '' ) {
      $info_string .= " <<a href=\"mailto:" . (string)$event->leader1_email . "\">" .
          (string)$event->leader1_email . "</a>>";
    }
    $info_string .= "</span>\n";

    // Render the location if present
    if ( $event->trip_location != '') {
      $info_string .= "<div class=\"amc-event-location\"><span class=\"key\">Location</span>: " .
          (string)$event->trip_location . "</div>\n";
    }

    // Close detail block wrap (.amc-event-detail-block)
    $info_string .= "</div>\n";

    return $info_string;

  }

}
