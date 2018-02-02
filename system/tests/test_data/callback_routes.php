<?php
/**
 * A holding class for route callback tests
 *
 * @group kohana
 *
 * @package    Unittest
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Route_Holder
{
    /**
     * Just an empty callback that doesn't match anything
     *
     * @param mixed $uri
     */
    public static function default_callback($uri)
    {
    }

    /**
     * Just an empty callback that matches everything
     *
     * @param mixed $uri
     *
     * @return array
     */
    public static function default_return_callback($uri)
    {
        return [

        ];
    }

    /**
     * Route callback for test_matches_returns_array_of_parameters_on_successful_match
     *
     * @param mixed $uri
     *
     * @return array
     */
    public static function matches_returns_array_of_parameters_on_successful_match($uri)
    {
        return [
            'controller' => 'welcome',
            'action' => 'index',
        ];
    }

    /**
     * Route callback for test_required_parameters_are_needed
     *
     * @param mixed $uri
     *
     * @return array
     */
    public static function required_parameters_are_needed($uri)
    {
        if (substr($uri, 0, 5) == 'admin') {
            return [
                'controller' => 'foo',
                'action' => 'bar',
            ];
        }
    }

    /**
     * Route callback for test reverse_routing_returns_routes_uri_if_route_is_static
     *
     * @param mixed $uri
     *
     * @return array
     */
    public static function reverse_routing_returns_routes_uri_if_route_is_static($uri)
    {
        if ($uri == 'info/about_us') {
            return [

            ];
        }
    }

    /**
     * Route callback for test route_filter_modify_params
     *
     * @param mixed $params
     *
     * @return array
     */
    public static function route_filter_modify_params_array(Route $route, $params)
    {
        $params['action'] = 'modified';

        return $params;
    }

    /**
     * Route callback for test route_filter_modify_params
     *
     * @param mixed $params
     *
     * @return array
     */
    public static function route_filter_modify_params_false(Route $route, $params)
    {
        return false;
    }
}
