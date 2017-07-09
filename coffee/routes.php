<?php
require_once './classes/Router.php';
$GLOBALS['routes'] = new Router;
$GLOBALS['routes']->add_route('get',  '/',                    './welcome');
$GLOBALS['routes']->add_route('get',  '/colophon/',           './colophon');
$GLOBALS['routes']->add_route('get',  '/products/',           './products/index');
$GLOBALS['routes']->add_route('get',  '/products/new',        './products/create');
$GLOBALS['routes']->add_route('get',  '/products/:id',        './products/show');
$GLOBALS['routes']->add_route('post', '/products/',           './products/create');
$GLOBALS['routes']->add_route('get',  '/products/:id/edit',   './products/update');
$GLOBALS['routes']->add_route('post', '/products/:id',        './products/update');
$GLOBALS['routes']->add_route('post', '/products/:id/delete', './products/destroy');
