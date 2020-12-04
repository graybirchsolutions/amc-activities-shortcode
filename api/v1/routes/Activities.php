<?php

/**
 * Read-Only API route to return a list of activities
 *
 * @link       https://graybirch.solutions
 * @since      1.1.0
 *
 * @package    AMC_actdb_shortcode
 * @subpackage AMC_actdb_shortcode/api
 * @author     Martin Jensen <marty@graybirch.solutions>
 *
 * @abstract
 **/

namespace AMCActdb\api\v1\routes;

use AMCActdb\api\v1\responses\Error;
use AMCActdb\api\v1\responses\Response;
use SimpleXMLElement;
use WP_Error;

class Activities extends PublicReadRoute
{
    public function requestArgsPlural()
    {
        return [
            'chapter' => [
                'required' => false,
                'type' => 'integer',
                'description' => 'The AMC Chapter Number. Required Parameter.',
                'minimum' => 1,
                'maximum' => 99,
            ],
            'committee' => [
                'required' => false,
                'type' => 'integer',
                'description' => 'The AMC Committee Code. Optional. If not supplied, all Chapter activites are returned.',
                'minimum' => 1,
                'maximum' => 40
            ],
            'activity' => [
                'required' => false,
                'type' => 'integer',
                'description' => 'The AMC Activity Code. Optional. If not supplied, all Committee activites are returned.',
                'minimum' => 1,
                'maximum' => 40
            ],
            'display' => [
                'required' => false,
                'type' => 'string',
                'description' => 'Information returned for display - either short or long. Optional. Default = short.',
                'enum' => array('short', 'long'),
                'default' => 'short'
            ],
            'limit' => [
                'required' => false,
                'type' => 'integer',
                'description' => 'The maximum number of activities to return. Optional. Default = 10.',
                'minimum' => 0,
                'default' => 10
            ]
        ];
    }

    public function requestArgsSingle()
    {
        return [
            'id' => [
                'required' => true,
                'type' => 'integer',
                'description' => 'The event ID.',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getItem(\WP_REST_Request $request)
    {
        return new Response(['message' => 'getItem not activated ... yet.'], 404, []);
    }

    /**
     * @inheritdoc
     */
    public function getItems(\WP_REST_Request $request)
    {
        $xmlstring = $this->fetchActivities($request);

        $activityList = new SimpleXMLElement($xmlstring);

        if ($activityList->getName() == 'errors') {
            $error = new WP_Error(
                'AMCError',
                'AMC ActDB API returned an error. Query paramaters in error_data.',
                [
                    'chapter' => $request->get_param('chapter'),
                    'committee' => $request->get_param('committee'),
                    'activity' => $request->get_param('activity'),
                    'limit' => $request->get_param('limit')
                ]
            );
            foreach ($activityList->error as $amcerr) {
                $error->add('AMCError', 'error Code returned = ' . (string)$amcerr->errorCode);
                $error->add('AMCError', 'error Message returned = ' . (string)$amcerr->message);
                $error->add('AMCError', 'error Field returned = ' . (string)$amcerr->field);
            }
            return new Response($error, 404, []);
        }

        $items = $this->prepareItemsForResponse($activityList, (int)$request->get_param('limit'));

        return new Response($items, 200, []);
    }

    /**
     * Fetch activities list from the AMC Activities Database XML API
     *
     * @param \WP_REST_Request $request
     * @return string
     */

    protected function fetchActivities(\WP_REST_Request $request)
    {
        $amc_url = AMC_ACTDB_BASE_URL . '?';

        $amc_url = $amc_url .  'chapter=' . $request->get_param('chapter');

        if ($request->has_param('committee')) {
            $amc_url = $amc_url . '&committee=' . $request->get_param('committee');
        }

        if ($request->has_param('activity')) {
            $amc_url = $amc_url . '&activity=' . $request->get_param('activity');
        }

        $xmlstring = file_get_contents($amc_url);

        return $xmlstring;
    }

    /**
     * Prepare a single activity for response.
     *
     * We are returning a subset of the information available for each event to allow the
     * front end to render an agenda-like calendar list of upcoming events.
     *
     * @param \SimpleXMLElement $event
     * @return array
     */

    protected function prepareItemForResponse(\SimpleXMLElement $event)
    {
        $eventResponse = [
            'trip_id' => (string)$event->trip_id,
            'trip_title' => (string)$event->trip_title,
            'trip_url' => AMC_ACTDB_BASE_EVENT_URL . (string)$event->trip_id,
            'trip_committee' => (string)$event->committee,
            'trip_datetime' => (string)$event->trip_start_date,
            'trip_status' => (string)$event->status,
            'trip_leader' => (string)$event->leader1,
            'trip_leader_email' => (string)$event->leader1_email,
            'trip_difficulty' => (string)$event->tripDifficulty,
            'trip_location' => (string)$event->trip_location
        ];

        $activityTypes = [];
        foreach ($event->activities->activity as $type) {
            $activityTypes[] .= $type;
        }

        $eventResponse['trip_activities'] = $activityTypes;

        return $eventResponse;
    }

    /**
     * Prepare a list of activities for response.
     *
     * @param \SimpleXMLElement $activities XML formatted list of activities from AMC ActDB.
     * @param $limit Integer representing the number of events to return
     * @return array
     */

    protected function prepareItemsForResponse(\SimpleXMLElement $activities, int $limit)
    {
        $items = [];
        $i = 1;
        foreach ($activities->trip as $event) {
            $items[] = $this->prepareItemForResponse($event);
            if ($i++ == (int)$limit) {
                break;
            }
        }
        return $items;
    }
    
    /**
     * Ovveride in subclass with a hardcoded string.
     */
    protected function routeBase(bool $isPlural)
    {
        if ($isPlural) {
            return "activities";
        } else {
            return "activity";
        }
    }
}
