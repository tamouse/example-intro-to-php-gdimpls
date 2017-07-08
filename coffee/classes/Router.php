<?php
require_once 'Route.php';

class Router
{
    function __construct()
    {
        $this->routes = [];
    }

    function __destruct()
    {
        $this->routes = null;
    }

    function add_route($method='',$path='', $action='')
    {
        $this->routes[] = new Route($method, $path, $action);
    }

    function get_route($method='', $path='')
    {
        $route = $this->find_route($method, $path);
        $params = $this->get_params($route, $path);
        $component = $this->get_component($route);

        return [
            'route' => $route,
            'params' => $params,
            'component' => $component
        ];
    }

    function find_route($method='', $path='')
    {
        foreach ($this->routes as $route) {
            if ($route->match_route($method, $path)) return $route;
        }
    }

    function get_params($route, $path)
    {
        return $route->translate_path($path);
    }

    function get_component($route)
    {
        return $route->component_name();
    }

}