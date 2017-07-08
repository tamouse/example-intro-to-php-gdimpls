<?php
require_once 'simpletest/autorun.php';
require_once './coffee/classes/Router.php';

class RouterTest extends UnitTestCase
{
    function setUp()
    {
        global $router;
        $router = new Router;
        $router->add_route('get', '/tests', 'tests/index');
        $router->add_route('post', '/tests', 'tests/create');
        $router->add_route('put', '/tests/:id', 'tests/update');
        $router->add_route('get', '/tests/:id/edit', 'tests/create');
    }

    function testRouterGetRoute()
    {
        global $router;
        $route = $router->get_route('get', '/tests');
        $this->assertTrue(array_key_exists('route', $route));
        $this->assertTrue(array_key_exists('params', $route));
        $this->assertTrue(array_key_exists('component', $route));
        $this->assertEqual($route['params'], []);
        $this->assertEqual($route['component'], 'tests/index.inc.php');
    }

    function testRouterGetRouteWithParams()
    {
        global $router;
        $route = $router->get_route('get', '/tests/123/edit');
        $this->assertTrue(array_key_exists('route', $route));
        $this->assertTrue(array_key_exists('params', $route));
        $this->assertTrue(array_key_exists('component', $route));
        $this->assertEqual($route['params'], ['id'=>'123']);
        $this->assertEqual($route['component'], 'tests/create.inc.php');

    }
}