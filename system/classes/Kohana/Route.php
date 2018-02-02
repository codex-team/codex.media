<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Routes are used to determine the controller and action for a requested URI.
 * Every route generates a regular expression which is used to match a URI
 * and a route. Routes may also contain keys which can be used to set the
 * controller, action, and parameters.
 *
 * Each <key> will be translated to a regular expression using a default
 * regular expression pattern. You can override the default pattern by providing
 * a pattern for the key:
 *
 *     // This route will only match when <id> is a digit
 *     Route::set('user', 'user/<action>/<id>', array('id' => '\d+'));
 *
 *     // This route will match when <path> is anything
 *     Route::set('file', '<path>', array('path' => '.*'));
 *
 * It is also possible to create optional segments by using parentheses in
 * the URI definition:
 *
 *     // This is the standard default route, and no keys are required
 *     Route::set('default', '(<controller>(/<action>(/<id>)))');
 *
 *     // This route only requires the <file> key
 *     Route::set('file', '(<path>/)<file>(.<format>)', array('path' => '.*', 'format' => '\w+'));
 *
 * Routes also provide a way to generate URIs (called "reverse routing"), which
 * makes them an extremely powerful and flexible way to generate internal links.
 *
 * @package    Kohana
 * @category   Base
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Route
{

    // Defines the pattern of a <segment>
    const REGEX_KEY = '<([a-zA-Z0-9_]++)>';

    // What can be part of a <segment> value
    const REGEX_SEGMENT = '[^/.,;?\n]++';

    // What must be escaped in the route regex
    const REGEX_ESCAPE = '[.\\+*?[^\\]${}=!|]';

    /**
     * @var string default protocol for all routes
     *
     * @example  'http://'
     */
    public static $default_protocol = 'http://';

    /**
     * @var array list of valid localhost entries
     */
    public static $localhosts = [false, '', 'local', 'localhost'];

    /**
     * @var string default action for all routes
     */
    public static $default_action = 'index';

    /**
     * @var bool Indicates whether routes are cached
     */
    public static $cache = false;

    /**
     * @var array
     */
    protected static $_routes = [];

    /**
     * Stores a named route and returns it. The "action" will always be set to
     * "index" if it is not defined.
     *
     *     Route::set('default', '(<controller>(/<action>(/<id>)))')
     *         ->defaults(array(
     *             'controller' => 'welcome',
     *         ));
     *
     * @param string $name  route name
     * @param string $uri   URI pattern
     * @param array  $regex regex patterns for route keys
     *
     * @return Route
     */
    public static function set($name, $uri = null, $regex = null)
    {
        return Route::$_routes[$name] = new Route($uri, $regex);
    }

    /**
     * Retrieves a named route.
     *
     *     $route = Route::get('default');
     *
     * @param string $name route name
     *
     * @throws Kohana_Exception
     *
     * @return Route
     */
    public static function get($name)
    {
        if (! isset(Route::$_routes[$name])) {
            throw new Kohana_Exception('The requested route does not exist: :route',
                [':route' => $name]);
        }

        return Route::$_routes[$name];
    }

    /**
     * Retrieves all named routes.
     *
     *     $routes = Route::all();
     *
     * @return array routes by name
     */
    public static function all()
    {
        return Route::$_routes;
    }

    /**
     * Get the name of a route.
     *
     *     $name = Route::name($route)
     *
     * @param Route $route instance
     *
     * @return string
     */
    public static function name(Route $route)
    {
        return array_search($route, Route::$_routes);
    }

    /**
     * Saves or loads the route cache. If your routes will remain the same for
     * a long period of time, use this to reload the routes from the cache
     * rather than redefining them on every page load.
     *
     *     if ( ! Route::cache())
     *     {
     *         // Set routes here
     *         Route::cache(TRUE);
     *     }
     *
     * @param bool $save   cache the current routes
     * @param bool $append append, rather than replace, cached routes when loading
     *
     * @return bool when loading routes
     *
     * @uses    Kohana::cache
     */
    public static function cache($save = false, $append = false)
    {
        if ($save === true) {
            try {
                // Cache all defined routes
                Kohana::cache('Route::cache()', Route::$_routes);
            } catch (Exception $e) {
                // We most likely have a lambda in a route, which cannot be cached
                throw new Kohana_Exception('One or more routes could not be cached (:message)', [
                        ':message' => $e->getMessage(),
                    ], 0, $e);
            }
        } else {
            if ($routes = Kohana::cache('Route::cache()')) {
                if ($append) {
                    // Append cached routes
                    Route::$_routes += $routes;
                } else {
                    // Replace existing routes
                    Route::$_routes = $routes;
                }

                // Routes were cached
                return Route::$cache = true;
            } else {
                // Routes were not cached
                return Route::$cache = false;
            }
        }
    }

    /**
     * Create a URL from a route name. This is a shortcut for:
     *
     *     echo URL::site(Route::get($name)->uri($params), $protocol);
     *
     * @param string $name     route name
     * @param array  $params   URI parameters
     * @param mixed  $protocol protocol string or boolean, adds protocol and domain
     *
     * @return string
     *
     * @since   3.0.7
     *
     * @uses    URL::site
     */
    public static function url($name, array $params = null, $protocol = null)
    {
        $route = Route::get($name);

        // Create a URI with the route and convert it to a URL
        if ($route->is_external()) {
            return Route::get($name)->uri($params);
        } else {
            return URL::site(Route::get($name)->uri($params), $protocol);
        }
    }

    /**
     * Returns the compiled regular expression for the route. This translates
     * keys and optional groups to a proper PCRE regular expression.
     *
     *     $compiled = Route::compile(
     *        '<controller>(/<action>(/<id>))',
     *         array(
     *           'controller' => '[a-z]+',
     *           'id' => '\d+',
     *         )
     *     );
     *
     * @uses    Route::REGEX_ESCAPE
     * @uses    Route::REGEX_SEGMENT
     *
     * @param mixed $uri
     *
     * @return string
     */
    public static function compile($uri, array $regex = null)
    {
        // The URI should be considered literal except for keys and optional parts
        // Escape everything preg_quote would escape except for : ( ) < >
        $expression = preg_replace('#' . Route::REGEX_ESCAPE . '#', '\\\\$0', $uri);

        if (strpos($expression, '(') !== false) {
            // Make optional parts of the URI non-capturing and optional
            $expression = str_replace(['(', ')'], ['(?:', ')?'], $expression);
        }

        // Insert default regex for keys
        $expression = str_replace(['<', '>'], ['(?P<', '>' . Route::REGEX_SEGMENT . ')'], $expression);

        if ($regex) {
            $search = $replace = [];
            foreach ($regex as $key => $value) {
                $search[] = "<$key>" . Route::REGEX_SEGMENT;
                $replace[] = "<$key>$value";
            }

            // Replace the default regex with the user-specified regex
            $expression = str_replace($search, $replace, $expression);
        }

        return '#^' . $expression . '$#uD';
    }

    /**
     * @var array route filters
     */
    protected $_filters = [];

    /**
     * @var string route URI
     */
    protected $_uri = '';

    /**
     * @var array
     */
    protected $_regex = [];

    /**
     * @var array
     */
    protected $_defaults = ['action' => 'index', 'host' => false];

    /**
     * @var string
     */
    protected $_route_regex;

    /**
     * Creates a new route. Sets the URI and regular expressions for keys.
     * Routes should always be created with [Route::set] or they will not
     * be properly stored.
     *
     *     $route = new Route($uri, $regex);
     *
     * The $uri parameter should be a string for basic regex matching.
     *
     *
     * @param string $uri   route URI pattern
     * @param array  $regex key patterns
     *
     * @uses    Route::_compile
     */
    public function __construct($uri = null, $regex = null)
    {
        if ($uri === null) {
            // Assume the route is from cache
            return;
        }

        if (! empty($uri)) {
            $this->_uri = $uri;
        }

        if (! empty($regex)) {
            $this->_regex = $regex;
        }

        // Store the compiled regex locally
        $this->_route_regex = Route::compile($uri, $regex);
    }

    /**
     * Provides default values for keys when they are not present. The default
     * action will always be "index" unless it is overloaded here.
     *
     *     $route->defaults(array(
     *         'controller' => 'welcome',
     *         'action'     => 'index'
     *     ));
     *
     * If no parameter is passed, this method will act as a getter.
     *
     * @param array $defaults key values
     *
     * @return $this or array
     */
    public function defaults(array $defaults = null)
    {
        if ($defaults === null) {
            return $this->_defaults;
        }

        $this->_defaults = $defaults;

        return $this;
    }

    /**
     * Filters to be run before route parameters are returned:
     *
     *     $route->filter(
     *         function(Route $route, $params, Request $request)
     *         {
     *             if ($request->method() !== HTTP_Request::POST)
     *             {
     *                 return FALSE; // This route only matches POST requests
     *             }
     *             if ($params AND $params['controller'] === 'welcome')
     *             {
     *                 $params['controller'] = 'home';
     *             }
     *
     *             return $params;
     *         }
     *     );
     *
     * To prevent a route from matching, return `FALSE`. To replace the route
     * parameters, return an array.
     *
     * [!!] Default parameters are added before filters are called!
     *
     * @param array $callback callback string, array, or closure
     *
     * @throws Kohana_Exception
     *
     * @return $this
     */
    public function filter($callback)
    {
        if (! is_callable($callback)) {
            throw new Kohana_Exception('Invalid Route::callback specified');
        }

        $this->_filters[] = $callback;

        return $this;
    }

    /**
     * Tests if the route matches a given URI. A successful match will return
     * all of the routed parameters as an array. A failed match will return
     * boolean FALSE.
     *
     *     // Params: controller = users, action = edit, id = 10
     *     $params = $route->matches('users/edit/10');
     *
     * This method should almost always be used within an if/else block:
     *
     *     if ($params = $route->matches($uri))
     *     {
     *         // Parse the parameters
     *     }
     *
     * @param string $uri URI to match
     *
     * @return array on success
     * @return FALSE on failure
     */
    public function matches(Request $request)
    {
        // Get the URI from the Request
        $uri = trim($request->uri(), '/');

        if (! preg_match($this->_route_regex, $uri, $matches)) {
            return false;
        }

        $params = [];
        foreach ($matches as $key => $value) {
            if (is_int($key)) {
                // Skip all unnamed keys
                continue;
            }

            // Set the value for all matched keys
            $params[$key] = $value;
        }

        foreach ($this->_defaults as $key => $value) {
            if (! isset($params[$key]) or $params[$key] === '') {
                // Set default values for any key that was not matched
                $params[$key] = $value;
            }
        }

        if (! empty($params['controller'])) {
            // PSR-0: Replace underscores with spaces, run ucwords, then replace underscore
            $params['controller'] = str_replace(' ', '_', ucwords(str_replace('_', ' ', $params['controller'])));
        }

        if (! empty($params['directory'])) {
            // PSR-0: Replace underscores with spaces, run ucwords, then replace underscore
            $params['directory'] = str_replace(' ', '_', ucwords(str_replace('_', ' ', $params['directory'])));
        }

        if ($this->_filters) {
            foreach ($this->_filters as $callback) {
                // Execute the filter giving it the route, params, and request
                $return = call_user_func($callback, $this, $params, $request);

                if ($return === false) {
                    // Filter has aborted the match
                    return false;
                } elseif (is_array($return)) {
                    // Filter has modified the parameters
                    $params = $return;
                }
            }
        }

        return $params;
    }

    /**
     * Returns whether this route is an external route
     * to a remote controller.
     *
     * @return bool
     */
    public function is_external()
    {
        return ! in_array(Arr::get($this->_defaults, 'host', false), Route::$localhosts);
    }

    /**
     * Generates a URI for the current route based on the parameters given.
     *
     *     // Using the "default" route: "users/profile/10"
     *     $route->uri(array(
     *         'controller' => 'users',
     *         'action'     => 'profile',
     *         'id'         => '10'
     *     ));
     *
     * @param array $params URI parameters
     *
     * @throws Kohana_Exception
     *
     * @return string
     *
     * @uses    Route::REGEX_Key
     */
    public function uri(array $params = null)
    {
        // Start with the routed URI
        $uri = $this->_uri;

        if (strpos($uri, '<') === false and strpos($uri, '(') === false) {
            // This is a static route, no need to replace anything

            if (! $this->is_external()) {
                return $uri;
            }

            // If the localhost setting does not have a protocol
            if (strpos($this->_defaults['host'], '://') === false) {
                // Use the default defined protocol
                $params['host'] = Route::$default_protocol . $this->_defaults['host'];
            } else {
                // Use the supplied host with protocol
                $params['host'] = $this->_defaults['host'];
            }

            // Compile the final uri and return it
            return rtrim($params['host'], '/') . '/' . $uri;
        }

        // Keep track of whether an optional param was replaced
        $provided_optional = false;

        while (preg_match('#\([^()]++\)#', $uri, $match)) {

            // Search for the matched value
            $search = $match[0];

            // Remove the parenthesis from the match as the replace
            $replace = substr($match[0], 1, -1);

            while (preg_match('#' . Route::REGEX_KEY . '#', $replace, $match)) {
                list($key, $param) = $match;

                if (isset($params[$param]) and $params[$param] !== Arr::get($this->_defaults, $param)) {
                    // Future optional params should be required
                    $provided_optional = true;

                    // Replace the key with the parameter value
                    $replace = str_replace($key, $params[$param], $replace);
                } elseif ($provided_optional) {
                    // Look for a default
                    if (isset($this->_defaults[$param])) {
                        $replace = str_replace($key, $this->_defaults[$param], $replace);
                    } else {
                        // Ungrouped parameters are required
                        throw new Kohana_Exception('Required route parameter not passed: :param', [
                            ':param' => $param,
                        ]);
                    }
                } else {
                    // This group has missing parameters
                    $replace = '';
                    break;
                }
            }

            // Replace the group in the URI
            $uri = str_replace($search, $replace, $uri);
        }

        while (preg_match('#' . Route::REGEX_KEY . '#', $uri, $match)) {
            list($key, $param) = $match;

            if (! isset($params[$param])) {
                // Look for a default
                if (isset($this->_defaults[$param])) {
                    $params[$param] = $this->_defaults[$param];
                } else {
                    // Ungrouped parameters are required
                    throw new Kohana_Exception('Required route parameter not passed: :param', [
                        ':param' => $param,
                    ]);
                }
            }

            $uri = str_replace($key, $params[$param], $uri);
        }

        // Trim all extra slashes from the URI
        $uri = preg_replace('#//+#', '/', rtrim($uri, '/'));

        if ($this->is_external()) {
            // Need to add the host to the URI
            $host = $this->_defaults['host'];

            if (strpos($host, '://') === false) {
                // Use the default defined protocol
                $host = Route::$default_protocol . $host;
            }

            // Clean up the host and prepend it to the URI
            $uri = rtrim($host, '/') . '/' . $uri;
        }

        return $uri;
    }
} // End Route
