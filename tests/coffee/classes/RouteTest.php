<?php
require_once 'simpletest/autorun.php';
require_once './coffee/classes/Route.php';

class RouteTest extends UnitTestCase
{
    function testNewRoute()
    {
        $route = new Route('get', '/tests/:id', 'tests/get');
        $this->assertNotNull($route);
        $this->assertEqual('tests/get.inc.php', $route->component_name());
        $params = $route->translate_path("/tests/123");
        $this->assertNotNull($params);
        $this->assertTrue(array_key_exists('id', $params));
        $this->assertEqual('123', $params['id']);
    }

    function testMatchRoute()
    {
        $route = new Route('get', '/tests/:id/new', 'tests/create');
        $this->assertTrue($route->match_route('get', '/tests/123/new'));
        $this->assertFalse($route->match_route('put', '/tests/123/new'));
        $this->assertFalse($route->match_route('get', '/tests'));
    }
}