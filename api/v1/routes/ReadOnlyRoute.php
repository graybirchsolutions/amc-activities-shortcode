<?php

/**
 * Abstract class to register a Read Only route for an API
 *
 * @link       https://graybirch.solutions
 * @since      1.1.0
 *
 * @package    AMC_activities_shortcode
 * @subpackage AMC_activities_shortcode/api
 * @author     Martin Jensen <marty@graybirch.solutions>
 *
 * @abstract
 **/

namespace AMCActivities\api\v1\routes;

use AMCActivities\api\v1\interfaces\Route;
use AMCActivities\api\v1\responses\Error;
use AMCActivities\api\v1\responses\Response;

abstract class ReadOnlyRoute implements Route
{
    /**
     * @inheritdoc
     */
    public function registerRoutes($namespace)
    {
        $base = $this->routeBase(true);
        register_rest_route(
            $namespace,
            '/' . $base,
            [
                [
                    'methods'         => \WP_REST_Server::READABLE,
                    'callback'        => [$this, 'getItems'],
                    'permission_callback' => [$this, 'getItemsPermissionsCheck'],
                    'args'            => $this->requestArgsPlural()
                ],
            ]
        );
        $base = $this->routeBase(false);
        register_rest_route(
            $namespace,
            '/' . $base,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [$this, 'getItem'],
                    'permission_callback' => [$this, 'getItemPermissionsCheck'],
                    'args'                => $this->requestArgsSingle()
                ],
            ]
        );
    }


    /**
     * Define query arguments for a multi-item query
     *
     * @return array
     */
    abstract public function requestArgsPlural();

    /**
     * Define query arguments for a single-item query
     *
     * @return array
     */
    abstract public function requestArgsSingle();

    /**
     * Check if a given request has access to get items
     *
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|bool
     */
    abstract public function getItemsPermissionsCheck(\WP_REST_Request $request);


    /**
     * Check if a given request has access to get a specific item
     *
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|bool
     */
    public function getItemPermissionsCheck(\WP_REST_Request $request)
    {
        return $this->getItemsPermissionsCheck($request);
    }

    /**
     * Get a collection of items
     *
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function getItems(\WP_REST_Request $request)
    {
        return $this->notImplementedResponse();
    }

    /**
     * Get one item from the collection
     *
     * @param \WP_REST_Request $request Full data about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function getItem(\WP_REST_Request $request)
    {
        return $this->notImplementedResponse();
    }

    /**
     * Return a 501 error for non-existant route
     *
     * @return response
     */
    protected function notImplementedResponse()
    {
        $error =  new Error('not-implemented-yet', __('Route Not Implemented :(', 'your-domain'));
        return new Response($error, 404, []);
    }

    /**
     * Get class shortname and use as base
     *
     * Probably better to ovveride in subclass with a hardcoded string.
     */
    protected function routeBase(bool $isPlural)
    {
        if ($isPlural) {
            return substr(strrchr(get_class($this), '\\'), 1) . 's';
        } else {
            return substr(strrchr(get_class($this), '\\'), 1);
        }
    }
}
