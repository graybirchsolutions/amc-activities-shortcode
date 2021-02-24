<?php

/**
 * AMC ActDB API v1 Bootstrap Class
 *
 * The API classes are based on the work of Josh Pollock published in Torque magazine.
 *    The original article is published at https://torquemag.io/2016/08/building-custom-rest-api-routes-using-object-oriented-php/
 *    The corresponding Github source code is found at https://github.com/Shelob9/rest-api-example
 *
 * This API provides a set of read-only routes as a proxy interface to the AMC Activities Database (ActDB) XML API.
 *
 * The ActDB XML API does not have CORS support, which prevents current browsers from issuing AJAX requests directly to that API from
 * different domains.
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

namespace AMCActivities\api\v1;

use AMCActivities\api\v1\interfaces\Route;

class Boot
{

    /**
     * @var array
     */
    protected $routes;

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * boot constructor.
     *
     * @param string $namespace Namespace for all routes
     */
    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Add a route to this API
     *
     * @param route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    /**
     * Create endpoints
     */
    public function registerAllRoutes()
    {
        if (!$this->booted && !empty($this->routes)) {
            /** @var Route $route */
            foreach ($this->routes as $route) {
                $route->registerRoutes($this->namespace);
            }

            $this->booted = true;
        }
    }
}
