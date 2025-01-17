<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Authex', 'action' => 'login']);

    $routes->connect('/login', ['controller' => 'Authex', 'action' => 'login']);
    $routes->connect('/register', ['controller' => 'Authex', 'action' => 'register']);
    $routes->connect('/logout', ['controller' => 'Authex', 'action' => 'logout']);
    $routes->connect('/dashboard', ['controller' => 'Users', 'action' => 'dashboard']);

    $routes->connect('/members', ['controller' => 'Users', 'action' => 'members']);
    $routes->connect('/members/add', ['controller' => 'Users', 'action' => 'addmember']);

    $routes->connect('/clients', ['controller' => 'Users', 'action' => 'clients']);
    $routes->connect('/clients/add', ['controller' => 'Users', 'action' => 'addclient']);

    $routes->connect('/projects/addtask', ['controller' => 'Projects', 'action' => 'addtask']);
    
    $routes->connect('/status', ['controller' => 'Settings', 'action' => 'systemstatus']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});

Router::prefix('api', function ($routes) {
    $routes->extensions(['json']);
    $routes->connect('/login', ['controller' => 'Api', 'action' => 'login']);
    $routes->connect('/projects', ['controller' => 'Api', 'action' => 'getprojects']);
    $routes->connect('/tasks', ['controller' => 'Api', 'action' => 'gettasks']);
    $routes->connect('/time', ['controller' => 'Api', 'action' => 'gettime']);
    $routes->connect('/storereport', ['controller' => 'Api', 'action' => 'storereport']);

    $routes->connect('/test', ['controller' => 'Api', 'action' => 'test']);
    $routes->fallbacks('InflectedRoute');
});
