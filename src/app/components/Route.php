<?php

namespace App\Components;

use App\Models\Model;
use App\View\View;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteCollector;
use App\Controllers\Site\IndexController;
use App\Controllers\Admin;

class Route
{
    private $method;
    private $url;

    public function __construct($method, $url)
    {
        $this->method = $method;
        $this->url = $url;
    }

    public function upload()
    {
        $router = new RouteCollector();
        $this->setRoutes($router);

        $dispatcher = new Dispatcher($router->getData());

        try {
            $dispatcher->dispatch($this->method, $this->processInput($this->url));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die();

            http_response_code(404);
            $model = new Model();
            $view = new View($model->getDefaultData());
            $view->generate('site/404.twig');;
        }
    }

    private function processInput($uri): string
    {
        return urldecode(parse_url($uri, PHP_URL_PATH));
    }

    /**
     * @param RouteCollector $router
     */
    private function setRoutes($router)
    {
        $router->get('/', [IndexController::class, 'showMainPage']);
        $router->post('change-language', [IndexController::class, 'actionChangeLanguage']);
        $router->get('portfolio', ['App\\Controllers\\Site\\PortfolioController', 'showPortfolioPage']);
        $router->get('portfolio/show-more/{count}', ['App\\Controllers\\Site\\PortfolioController', 'showMore']);
        $router->get('company', ['App\\Controllers\\Site\\CompanyController', 'showCompanyPage']);
        $router->get('delivery', ['App\\Controllers\\Site\\DeliveryController', 'showDeliveryPage']);
        $router->get('contacts', ['App\\Controllers\\Site\\ContactController', 'index']);
        $router->get('comments', ['App\\Controllers\\Site\\CommentController', 'index']);
        $router->get('comments/show-more/{count}', ['App\\Controllers\\Site\\CommentController', 'showMore']);
        $router->post('comments/create-comment', ['App\\Controllers\\Site\\CommentController', 'createComment']);
        $router->get('blog', ['App\\Controllers\\Site\\BlogController', 'index']);
        $router->get('blog/show-more/{count}', ['App\\Controllers\\Site\\BlogController', 'showMore']);
        $router->get('blog/{alias}/{id}', ['App\\Controllers\\Site\\BlogController', 'showOne']);
        $router->post('search', ['App\\Controllers\\Site\\SearchController', 'actionIndex']);

        $router->get('new', ['App\\Controllers\\Site\\NewController', 'index']);
        $router->get('new/show-more/{count}', ['App\\Controllers\\Site\\NewController', 'showMore']);
        $router->get('new/{alias}/{id}', ['App\\Controllers\\Site\\NewController', 'showOne']);

        $router->get('catalog/{alias}/{id}', ['App\\Controllers\\Site\\CatalogController', 'showCatalogPage']);
        $router->get('catalog/show-more/{categoryId}/{count}', ['App\\Controllers\\Site\\CatalogController', 'getMoreByCategory']);
        $router->get('catalog/comparison-products', ['App\\Controllers\\Site\\CatalogController', 'showProductComparisonPage']);
        $router->post('price-list/order', ['App\\Controllers\\Site\\PriceListController', 'sendPriceListToClient']);

        $router->get('product/{alias}/{id}', ['App\\Controllers\\Site\\ProductController', 'showProductPage']);
        $router->post('product/add-to-comparison', ['App\\Controllers\\Site\\ProductController', 'addToComparison']);
        $router->post('product/delete-from-comparison', ['App\\Controllers\\Site\\ProductController', 'deleteFromComparison']);

        $router->get('user/login', ['App\\Controllers\\Site\\UserController', 'showLoginPage']);
        $router->post('user/login', ['App\\Controllers\\Site\\UserController', 'actionLogin']);
        $router->get('user/logout', ['App\\Controllers\\Site\\UserController', 'actionLogout']);
        $router->get('user/restore-password', ['App\\Controllers\\Site\\UserController', 'showRestorePasswordPage']);
        $router->post('user/restore-password', ['App\\Controllers\\Site\\UserController', 'restorePassword']);
        $router->get('user/update-password/{activeHex}', ['App\\Controllers\\Site\\UserController', 'showUpdatePasswordPage']);
        $router->post('user/update-password', ['App\\Controllers\\Site\\UserController', 'updatePassword']);

        // Страница уведомелний о cookie
        $router->get('cookie', ['App\\Controllers\\site\\CookieController', 'showCookiePage']);


        // Главная страница админки
        $router->get('admin', [Admin\IndexController::class, 'actionIndex']);

        // Язык
        $router->any('admin/language', [Admin\LanguagesController::class, 'actionIndex']);
        $router->get('admin/language/create', [Admin\LanguagesController::class, 'actionShowCreatePage']);
        $router->post('admin/language/create', [Admin\LanguagesController::class, 'create']);
        $router->get('admin/language/update/{id}', [Admin\LanguagesController::class, 'actionShowUpdatePage']);
        $router->post('admin/language/update/{id}', [Admin\LanguagesController::class, 'update']);
        $router->get('admin/language/delete/{id}', [Admin\LanguagesController::class, 'actionShowDeletePage']);
        $router->post('admin/language/delete', [Admin\LanguagesController::class, 'delete']);

        // Страницы категорий
        $router->any('admin/product', [Admin\ProductController::class, 'actionIndex']);
        $router->get('admin/product/create', [Admin\ProductController::class, 'actionShowCreatePage']);
        $router->post('admin/product/create', [Admin\ProductController::class, 'create']);
        $router->get('admin/product/update/{id}', [Admin\ProductController::class, 'actionShowUpdatePage']);
        $router->post('admin/product/update/{id}', [Admin\ProductController::class, 'update']);
        $router->get('admin/product/delete/{id}', [Admin\ProductController::class, 'actionShowDeletePage']);
        $router->post('admin/product/delete', [Admin\ProductController::class, 'delete']);
        $router->get('admin/product/photo/delete/{id}/{photoId}', [Admin\ProductController::class, 'photoDelete']);

        // Страницы товаров
        $router->any('admin/category', ['App\\Controllers\\Admin\\CategoryController', 'actionIndex']);
        $router->get('admin/category/create', ['App\\Controllers\\Admin\\CategoryController', 'actionShowCreatePage']);
        $router->post('admin/category/create', ['App\\Controllers\\Admin\\CategoryController', 'createCategory']);
        $router->get('admin/category/update/{id}', ['App\\Controllers\\Admin\\CategoryController', 'actionShowUpdatePage']);
        $router->post('admin/category/update/{id}', ['App\\Controllers\\Admin\\CategoryController', 'updateCategory']);
        $router->get('admin/category/delete/{id}', ['App\\Controllers\\Admin\\CategoryController', 'actionShowDeletePage']);
        $router->post('admin/category/delete/{id}', ['App\\Controllers\\Admin\\CategoryController', 'deleteCategory']);
        $router->get('admin/category/photo/delete/{id}/{photoId}', ['App\\Controllers\\Admin\\CategoryController', 'photoDelete']);

        // Страницы новостей
        $router->any('admin/blog', ['App\\Controllers\\Admin\\BlogController', 'actionIndex']);
        $router->get('admin/blog/create', ['App\\Controllers\\Admin\\BlogController', 'actionShowCreatePage']);
        $router->post('admin/blog/create', ['App\\Controllers\\Admin\\BlogController', 'create']);
        $router->get('admin/blog/update/{id}', ['App\\Controllers\\Admin\\BlogController', 'actionShowUpdatePage']);
        $router->post('admin/blog/update/{id}', ['App\\Controllers\\Admin\\BlogController', 'update']);
        $router->get('admin/blog/delete/{id}', ['App\\Controllers\\Admin\\BlogController', 'actionShowDeletePage']);
        $router->post('admin/blog/delete/{id}', ['App\\Controllers\\Admin\\BlogController', 'delete']);

        // Страницы новостей
        $router->any('admin/new', ['App\\Controllers\\Admin\\NewController', 'actionIndex']);
        $router->get('admin/new/create', ['App\\Controllers\\Admin\\NewController', 'actionShowCreatePage']);
        $router->post('admin/new/create', ['App\\Controllers\\Admin\\NewController', 'create']);
        $router->get('admin/new/update/{id}', ['App\\Controllers\\Admin\\NewController', 'actionShowUpdatePage']);
        $router->post('admin/new/update/{id}', ['App\\Controllers\\Admin\\NewController', 'update']);
        $router->get('admin/new/delete/{id}', ['App\\Controllers\\Admin\\NewController', 'actionShowDeletePage']);
        $router->post('admin/new/delete/{id}', ['App\\Controllers\\Admin\\NewController', 'delete']);


        // Страницы контента для остальных страниц
        $router->any('admin/page', ['App\\Controllers\\Admin\\PageController', 'actionIndex']);
        $router->get('admin/page/update/{id}', ['App\\Controllers\\Admin\\PageController', 'actionShowUpdatePage']);
        $router->post('admin/page/update/{id}', ['App\\Controllers\\Admin\\PageController', 'update']);

        // Страницы портфолио
        $router->any('admin/portfolio', ['App\\Controllers\\Admin\\PortfolioController', 'actionIndex']);
        $router->get('admin/portfolio/create', ['App\\Controllers\\Admin\\PortfolioController', 'actionShowCreatePage']);
        $router->post('admin/portfolio/create', ['App\\Controllers\\Admin\\PortfolioController', 'create']);
        $router->get('admin/portfolio/update/{id}', ['App\\Controllers\\Admin\\PortfolioController', 'actionShowUpdatePage']);
        $router->post('admin/portfolio/update/{id}', ['App\\Controllers\\Admin\\PortfolioController', 'update']);
        $router->get('admin/portfolio/delete/{id}', ['App\\Controllers\\Admin\\PortfolioController', 'actionShowDeletePage']);
        $router->post('admin/portfolio/delete/{id}', ['App\\Controllers\\Admin\\PortfolioController', 'delete']);

        // Страницы отзывов
        $router->any('admin/comments', ['App\\Controllers\\Admin\\CommentController', 'actionIndex']);
        $router->get('admin/comments/publish/{id}', ['App\\Controllers\\Admin\\CommentController', 'publish']);
        $router->get('admin/comments/create/{id}', ['App\\Controllers\\Admin\\CommentController', 'actionShowCreatePage']);
        $router->post('admin/comments/create/{id}', ['App\\Controllers\\Admin\\CommentController', 'create']);
        $router->get('admin/comments/delete/{id}', ['App\\Controllers\\Admin\\CommentController', 'actionShowDeletePage']);
        $router->post('admin/comments/delete/{id}', ['App\\Controllers\\Admin\\CommentController', 'delete']);

        $router->any('admin/user', ['App\\Controllers\\Admin\\UserController', 'actionIndex']);
        $router->get('admin/user/create', ['App\\Controllers\\Admin\\UserController', 'actionShowCreatePage']);
        $router->post('admin/user/create', ['App\\Controllers\\Admin\\UserController', 'create']);
        $router->get('admin/user/update/{id}', ['App\\Controllers\\Admin\\UserController', 'actionShowUpdatePage']);
        $router->post('admin/user/update/{id}', ['App\\Controllers\\Admin\\UserController', 'update']);
        $router->get('admin/user/delete/{id}', ['App\\Controllers\\Admin\\UserController', 'actionShowDeletePage']);
        $router->post('admin/user/delete/{id}', ['App\\Controllers\\Admin\\UserController', 'delete']);


        $router->any('admin/price-list', ['App\\Controllers\\Admin\\PriceListController', 'actionIndex']);
        $router->get('admin/price-list/create', ['App\\Controllers\\Admin\\PriceListController', 'actionShowCreatePage']);
        $router->post('admin/price-list/create', ['App\\Controllers\\Admin\\PriceListController', 'create']);
        $router->get('admin/price-list/update/{id}', ['App\\Controllers\\Admin\\PriceListController', 'actionShowUpdatePage']);
        $router->post('admin/price-list/update/{id}', ['App\\Controllers\\Admin\\PriceListController', 'update']);
        $router->get('admin/price-list/delete/{id}', ['App\\Controllers\\Admin\\PriceListController', 'actionShowDeletePage']);
        $router->post('admin/price-list/delete/{id}', ['App\\Controllers\\Admin\\PriceListController', 'delete']);
        $router->any('admin/price-list-order', ['App\\Controllers\\Admin\\PriceListController', 'actionOrder']);
    }
}

