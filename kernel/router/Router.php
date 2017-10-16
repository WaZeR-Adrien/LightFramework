<?php
namespace Kernel\Router;
use Controllers\Error\Error404;

class Router
{
    private $_url;
    private $_routes = [];
    private $_namedRoutes = [];

    /**
     * Router constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->_url = $url;
    }

    /**
     * @param $path
     * @param $callable
     * @param null $name
     * @param null $needLogin
     * @return Route
     */
    public function get($path, $callable, $name = null, $needLogin = null)
    {
        return $this->add($path, $callable, $name, $needLogin, 'GET');
    }

    /**
     * @param $path
     * @param $callable
     * @param null $name$name
     * @param null $needLogin
     * @return Route
     */
    public function post($path, $callable, $name  = null, $needLogin = null)
    {
        return $this->add($path, $callable, $name, $needLogin, 'POST');
    }

    /**
     * @param $path
     * @param $callable
     * @param $name
     * @param $needLogin
     * @param $method
     * @return Route
     */
    private function add($path, $callable, $name, $needLogin, $method)
    {
        $route = new Route($path, $callable, $needLogin);
        $this->_routes[$method][] = $route;
        if (is_string($callable) && is_null($name)) {
            $name = $callable;
        }
        if ($name) {
            $this->_namedRoutes[$name] = $route;
        }
        return $route;
    }

    /**
     * @return mixed
     * @throws RouterException
     */
    public function run()
    {
        if (!isset($this->_routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD doesn\'t exist');
        }

        try {
            foreach ($this->_routes[$_SERVER['REQUEST_METHOD']] as $route) {
                if ($route->match($this->_url)) {
                    return $route->call();
                }
            }
            throw new RouterException('No matching routes', 1);
        }
        catch (RouterException $e) {
            if ($e->getCode() === 1) { Error404::index(); }
        }

    }

    /**
     * @param $name
     * @param array $params
     * @return mixed
     * @throws RouterException
     */
    public function url($name, $params = [])
    {
        if (!isset($this->_namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }
        return $this->_namedRoutes[$name]->getUrl($params);
    }
}