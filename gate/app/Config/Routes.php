<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------------------------------------------------------
// Default Route
// --------------------------------------------------------------------
$routes->match(['get', 'head'], '/', function() {
    return view('landing');
});// --------------------------------------------------------------------
// Admin Routes
// --------------------------------------------------------------------
// app/Config/Routes.php
$routes->post('api/scan', 'Api::scan');
$routes->group('admin', static function ($routes) {

    // Auth Routes
    $routes->get('login', 'Admin\Auth::index');
    $routes->post('login/auth', 'Admin\Auth::attemptLogin');

    // Registration Routes
    $routes->get('register', 'Admin\Auth::register');
    $routes->post('register/auth', 'Admin\Auth::attemptRegister');

    $routes->get('logout', 'Admin\Auth::logout');

    // Dashboard Route
    $routes->get('dashboard', 'Admin\Dashboard::index', ['filter' => 'authGuard']);

    // --- ADMIN USERS MANAGEMENT ROUTES ---
    $routes->get('users', 'Admin\Users::index', ['filter' => 'authGuard']);

    // Students CRUD
    $routes->post('users/createStudent', 'Admin\Users::createStudent', ['filter' => 'authGuard']);
    $routes->post('users/editStudent/(:num)', 'Admin\Users::editStudent/$1', ['filter' => 'authGuard']);
    $routes->get('users/deleteStudent/(:num)', 'Admin\Users::deleteStudent/$1', ['filter' => 'authGuard']);
    $routes->post('users/updateStudent/(:num)', 'Admin\Users::updateStudent/$1', ['filter' => 'authGuard']);

    // Routes for Admin Items Management
    $routes->get('items', 'Admin\Items::index', ['filter' => 'authGuard']);
    $routes->get('items/check-latest-scan', 'Admin\Items::checkLatestScan', ['filter' => 'authGuard']);
    $routes->post('items/approveItem/(:num)', 'Admin\Items::approveItem/$1', ['filter' => 'authGuard']);
    $routes->get('items/process/(:any)/(:num)', 'Admin\Items::process/$1/$2', ['filter' => 'authGuard']);

    // Guards CRUD
    $routes->post('users/createGuard', 'Admin\Users::createGuard', ['filter' => 'authGuard']);
    $routes->post('users/editGuard/(:num)', 'Admin\Users::editGuard/$1', ['filter' => 'authGuard']);
    $routes->get('users/deleteGuard/(:num)', 'Admin\Users::deleteGuard/$1', ['filter' => 'authGuard']);
    $routes->post('users/updateGuard/(:num)', 'Admin\Users::updateGuard/$1', ['filter' => 'authGuard']);

    // Admins CRUD
    $routes->post('users/generate-admin-key', 'Admin\Users::generateAdminKey', ['filter' => 'authGuard']);
    $routes->post('users/createAdmin', 'Admin\Users::createAdmin', ['filter' => 'authGuard']);
    $routes->post('users/editAdmin/(:num)', 'Admin\Users::editAdmin/$1', ['filter' => 'authGuard']);
    $routes->get('users/deleteAdmin/(:num)', 'Admin\Users::deleteAdmin/$1', ['filter' => 'authGuard']);
    $routes->post('users/updateAdmin/(:num)', 'Admin\Users::updateAdmin/$1', ['filter' => 'authGuard']);

    // Visitors Management Routes
    $routes->get('visitors', 'Admin\Visitors::index', ['filter' => 'authGuard']);
    $routes->get('visitors/force-checkout/(:num)', 'Admin\Visitors::forceCheckout/$1', ['filter' => 'authGuard']);
    $routes->get('visitors/delete-tag/(:num)', 'Admin\Visitors::deleteTag/$1');
    $routes->post('visitors/add-tag', 'Admin\Visitors::addTag', ['filter' => 'authGuard']);
    $routes->get('visitors/check-tag', 'Admin\Visitors::checkTag', ['filter' => 'authGuard']);
    $routes->get('visitors/delete-log/(:num)', 'Admin\Visitors::deleteLog/$1');

    // Item Reports Routes
    $routes->get('item-reports', 'Admin\ItemReports::index');
    $routes->post('item-reports/resolve/(:num)', 'Admin\ItemReports::resolve/$1');
    // Admin Profile Route
    $routes->get('profile', 'Admin\Profile::index', ['filter' => 'authGuard']);
    $routes->post('profile/update', 'Admin\Profile::update', ['filter' => 'authGuard']);
});
// Guard Routes
$routes->group('guard', function($routes) {
    // Auth
    $routes->get('login', 'Guard\Auth::index');
    $routes->post('login/auth', 'Guard\Auth::login');
    $routes->get('logout', 'Guard\Auth::logout');

    // Dashboard
    $routes->get('dashboard', 'Guard\Dashboard::index');
    $routes->post('log-visitor', 'Guard\Dashboard::logVisitor');

    // Actions
    $routes->post('check-in', 'Guard\Dashboard::checkIn');
    $routes->post('check-out', 'Guard\Dashboard::checkOut');

    // Pending Item Approvals
    $routes->get('items/pending', 'Guard\Items::pending');
    $routes->get('items/process/(:segment)/(:num)', 'Guard\Items::process/$1/$2');

    // Guard Profile
    $routes->get('profile', 'Guard\Dashboard::profile');
    $routes->post('profile/update', 'Guard\Dashboard::updateProfile');

    // API Scanner
    $routes->get('check-latest-scan', 'Guard\Dashboard::checkLatestScan');
});
// Student Routes
$routes->group('student', function($routes) {
    // Auth
    $routes->get('login', 'Student\Auth::index');
    $routes->post('login/auth', 'Student\Auth::login');
    $routes->get('register', 'Student\Auth::register');
    $routes->post('register/save', 'Student\Auth::save');
    $routes->get('logout', 'Student\Auth::logout');
    $routes->get('verifyEmail/(:any)', 'Student\Auth::verifyEmail/$1');
    $routes->get('resendVerification/(:any)', 'Student\Auth::resendVerification/$1');
    $routes->get('forgot-password', 'Student\Auth::forgotPassword');
    $routes->post('forgot-password/send', 'Student\Auth::sendResetLink');
    $routes->get('reset-password/(:any)', 'Student\Auth::resetPassword/$1');
    $routes->post('reset-password/update', 'Student\Auth::updatePassword');

    // Dashboard & Sidebar Pages (MPA Structure)
    $routes->get('dashboard', 'Student\Dashboard::index', ['filter' => 'studentAuth']);
    $routes->get('profile', 'Student\Dashboard::profile', ['filter' => 'studentAuth']);
    $routes->post('profile/update', 'Student\Dashboard::updateProfile', ['filter' => 'studentAuth']);
    $routes->post('accept-terms', 'Student\Dashboard::acceptTerms');

    // NEW: The routes matching your sidebar.php links
    $routes->get('items/registration', 'Student\Dashboard::itemRegistration', ['filter' => 'studentAuth']);
    $routes->get('items/registered', 'Student\Dashboard::registeredItems', ['filter' => 'studentAuth']);
    $routes->get('items/remove', 'Student\Dashboard::removeItem', ['filter' => 'studentAuth']);
    $routes->get('items/report', 'Student\Dashboard::reportItem', ['filter' => 'studentAuth']);
    $routes->get('items/history', 'Student\Dashboard::history', ['filter' => 'studentAuth']);

    // Item Management Actions (POST/Logic)
    $routes->post('items/update/(:num)', 'Student\Items::update/$1');
    $routes->post('items/store', 'Student\Items::store');
    $routes->post('items/request-unregister/(:num)', 'Student\Items::requestUnregister/$1');
    $routes->post('items/report', 'Student\Items::report');
    $routes->get('items/mark-found/(:num)', 'Student\Items::markFound/$1');
});