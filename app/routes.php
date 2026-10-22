<?php
declare(strict_types=1);

use App\Controllers\Admin\AuthController as AdminAuth;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\RequestController as AdminRequests;
use App\Controllers\Admin\ReviewController as AdminReviews;
use App\Controllers\Admin\ServiceController as AdminServices;
use App\Controllers\ContactController;
use App\Controllers\PageController;
use App\Controllers\ProcurementController;
use App\Controllers\ReviewController;
use App\Controllers\SeoController;
use App\Controllers\SetupController;
use App\Router;

$router = new Router();

$router->get('/', [PageController::class, 'home']);
$router->get('/about', [PageController::class, 'about']);
$router->get('/services', [PageController::class, 'services']);
$router->get('/services/{slug}', [PageController::class, 'service']);
$router->get('/request-procurement', [ProcurementController::class, 'show']);
$router->post('/request-procurement', [ProcurementController::class, 'store']);
$router->get('/reviews', [ReviewController::class, 'index']);
$router->post('/reviews', [ReviewController::class, 'store']);
$router->get('/contact', [ContactController::class, 'show']);
$router->post('/contact', [ContactController::class, 'store']);
$router->get('/privacy', [PageController::class, 'privacy']);
$router->get('/terms', [PageController::class, 'terms']);
$router->get('/sitemap.xml', [SeoController::class, 'sitemap']);
$router->get('/robots.txt', [SeoController::class, 'robots']);
$router->get('/setup', [SetupController::class, 'show']);
$router->post('/setup', [SetupController::class, 'store']);

$router->get('/admin', [DashboardController::class, 'index']);
$router->get('/admin/login', [AdminAuth::class, 'showLogin']);
$router->post('/admin/login', [AdminAuth::class, 'login']);
$router->post('/admin/logout', [AdminAuth::class, 'logout']);
$router->get('/admin/requests', [AdminRequests::class, 'index']);
$router->get('/admin/requests/export', [AdminRequests::class, 'export']);
$router->post('/admin/requests/status', [AdminRequests::class, 'update']);
$router->get('/admin/requests/{id}/file', [AdminRequests::class, 'file']);
$router->get('/admin/requests/{id}', [AdminRequests::class, 'show']);
$router->get('/admin/reviews', [AdminReviews::class, 'index']);
$router->post('/admin/reviews', [AdminReviews::class, 'action']);
$router->get('/admin/services', [AdminServices::class, 'index']);
$router->post('/admin/services', [AdminServices::class, 'save']);
$router->post('/admin/services/delete', [AdminServices::class, 'delete']);

return $router;
