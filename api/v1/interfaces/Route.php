<?php

/**
 * The file that defines the custom API endpoints for the plugin
 *
 * @link       https://graybirch.solutions
 * @since      1.1.0
 *
 * @package    AMC_actdb_shortcode
 * @subpackage AMC_actdb_shortcode/api
 * @author     Martin Jensen <marty@graybirch.solutions>
 **/

namespace AMCActdb\api\v1\interfaces;

interface Route
{
    public function registerRoutes($namespace);
}
