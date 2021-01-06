<?php

/**
 * Base class to implement a read-only route for an API
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

abstract class PublicReadRoute extends ReadOnly
{
    public function getItemsPermissionsCheck(\WP_REST_Request $request)
    {
        return true;
    }
}
