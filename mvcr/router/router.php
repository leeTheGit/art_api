<?php
namespace mvcr\router;
/*
 * Router manager class
 * Statically stores all routes. The router manager is responsible for applying
 * consistent URI patterns such as having a slash at the end as well as
 * redirecting the client. If the routes don't match or it does but the
 * controller doesn't exist, it looks for the slash at the end of the path. If
 * it does not exist, it redirects with the slash appended and tries one more
 * time. The reason why the non-slash is tried is some scripts and files behave
 * differently to certain URIs (ex. javascript files which include others).
 *
 * @package router
 */

class Router
{

    // Array of routes
    private static $routes = array(self::PRIORITY_HIGH   => array(),
                                   self::PRIORITY_NORMAL => array(),
                                   self::PRIORITY_LOW    => array());
    // Matching pattern method (ROUTE/REDIRECT constant)
    private static $method;
    // Source that provided the route scanned. This is recorded along with
    // router::add() when the route is passed in hook_routes().
    private static $source;
    // Matching pattern
    private static $pattern;
    // Matching controller after preg_replace()
    private static $ctrl;
    // Flag for scanning routes
    private static $scanned = FALSE;

    private $path;
    private $query;
    private $param;


    // Route uses PCRE
    const ROUTE_PCRE = 0;
    // Route is static
    const ROUTE_STATIC = 1;
    // Redirect using PCRE
    const REDIRECT_PCRE = 2;
    // Redirect with literal strings
    const REDIRECT_STATIC = 3;
    // High priority route, used for "emergency" pages or redirects
    const PRIORITY_HIGH = 0;
    // Normal priority route, this is the most common
    const PRIORITY_NORMAL = 1;
    // Low priority route, used for special 404s or very generic routes (pages)
    const PRIORITY_LOW = 2;

    public function __construct($request)
    {
        $this->path  = $request->uri;
        $this->query = $request->data;
    }


    private function scan($force = FALSE)
    {
        $found = FALSE;
        if (!self::$scanned || $force) {
            foreach (self::$routes as $priority => $routes)  {

            if ($found) {
                    break;
                }
                foreach ($routes as $route) {
                    if ($found) {
                        break;
                    }
                    unset($ctrl, $redirect);
                    list($pattern, $replacement, $method, $source) = $route;

                    // logThis($route);
                    // logThis($pattern);


                    switch ($method)
                    {
                        case self::ROUTE_STATIC:
                            // echo "<br>";
                            // echo "The pattern: " .$pattern . "<br>";
                            // echo "The $this->path: " . $this->path . "<br>";
                            // echo "The replacement: " . $replacement . "<br>";
                            if ($this->path === $pattern) {
                                // echo "<br />The Pattern Matched!!<br />";
                                $ctrl = $replacement;
                                // logThis($ctrl);
                            }
                        break;
                        case self::ROUTE_PCRE:

                            if (preg_match($pattern, $this->path)) {
                                $ctrl = preg_replace($pattern, $replacement, $this->path);
                            }
                        break;
                        case self::REDIRECT_STATIC:
                            if ($this->path === $pattern) {
                                $redirect = $replacement;
                            }
                        break;
                        case self::REDIRECT_PCRE:
                            if (preg_match($pattern, $this->path)) {
                                $redirect = preg_replace($pattern, $replacement, $this->path);
                            }
                        break;
                    }
                    if (isset($ctrl) || isset($redirect)) {
                        if (isset($ctrl) && is_readable($ctrl)) {
                            self::$pattern = $pattern;
                            self::$ctrl    = $ctrl;
                            self::$method  = $method;
                            self::$source  = $source;
                            $found = TRUE;
                        }
                    }
                }
            }
            if (!self::$ctrl && !isset($redirect) && substr($this->path, -1) !== '/') {
                $redirect = $this->path.'/';
                if (strlen($this->param)) {
                    $redirect .= '?'.$this->param;
                }
            }
            if (isset($redirect)) {
                header('Location: '.$redirect);
                exit;
            }
            self::$scanned = !$force;
        }
    }

    /*
     * Controller called for the matching route
     * @return string
     */
    public function controller($scan = FALSE)
    {
        self::scan($scan);
        return self::$ctrl;
    }

    /*
     * Pattern of the matching route
     * @return string
     */
    public function pattern($scan = FALSE)
    {
        self::scan($scan);
        return self::$pattern;
    }

    /*
     * @return int
     */
    public function method($scan = FALSE)
    {
        self::scan($scan);
        return self::$method;
    }

    /*
     * Gets the source that provided the matching route
     * @return string|NULL
     */
    public function source($scan = FALSE)
    {
        self::scan($scan);
        return self::$source;
    }

    /*
     * Register a single route for the system
     * If $route is router::ROUTE_PCRE, both $pattern and $ctrl can be in PCRE
     * string format for pcre_replace(). if $route is router::ROUTE_STATIC then
     * it will do a fast string compare to $this->path. $ctrl must always be an
     * absolute filename.
     *
     * @return void
     */
    public static function add($pattern, $ctrl, $route = Router::ROUTE_STATIC, $priority = Router::PRIORITY_NORMAL, $source = NULL)
    {
        self::$routes[$priority][] = array($pattern, $ctrl, $route, $source);
    }

}

?>
