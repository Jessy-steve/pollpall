<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('auth', 'Auth::index');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('auth/register', 'Auth::register');
$routes->get('auth/save', 'Auth::save');
$routes->get('auth/check', 'Auth::check');
$routes->get('auth/activate/(:any)', 'Auth::activate/$1');
$routes->get('auth/forgot_password', 'Auth::forgot_password');
$routes->get('auth/reset_password/(:any)', 'Auth::reset_password/$1');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/reset_password/(:any)', 'Auth::reset_password/$1');
$routes->post('auth/send_reset_link', 'Auth::send_reset_link');
$routes->post('auth/check', 'Auth::check');
$routes->post('auth/save', 'Auth::save');
$routes->post('auth', 'Auth::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
