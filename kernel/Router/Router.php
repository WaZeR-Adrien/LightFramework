<?php
namespace Kernel\Router;

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
     * @return Route
     */
    public function get($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    /**
     * @param $path
     * @param $callable
     * @param null $name
     * @return Route
     */
    public function post($path, $callable, $name  = null)
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    /**
     * @param $path
     * @param $callable
     * @param $name
     * @param $method
     * @return Route
     */
    private function add($path, $callable, $name, $method)
    {
        $route = new Route($path, $callable);
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

        foreach ($this->_routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->_url)) {
                return $route->call();
            }
        }

        throw new RouterException('No matching routes');
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