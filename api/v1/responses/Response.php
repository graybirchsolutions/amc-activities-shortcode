<?php

/**
 * Response class return a response to an API call
 *
 * @link       https://graybirch.solutions
 * @since      1.1.0
 *
 * @package    AMC_activities_shortcode
 * @subpackage AMC_activities_shortcode/api
 * @author     Martin Jensen <marty@graybirch.solutions>
 **/

namespace AMCActivities\api\v1\responses;

class Response extends \WP_REST_Response
{
    public function __construct($data, $status, array $headers)
    {
        parent::__construct($data, $status, $headers);
        if (empty($data)) {
            $this->set_status(204);
        }
    }
}
