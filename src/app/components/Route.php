<?php

namespace App\Components;

use App\Controller\Admin\LanguagesController;
use App\Model\Model;
use App\View\View;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteCollector;
use App\Controller\Admin\ProductController;
use App\Controller\Site\IndexController;

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
        $router->get('portfolio', ['App\\Controller\\Site\\PortfolioController', 'showPortfolioPage']);
        $router->get('portfolio/show-more/{count}', ['App\\Controller\\Site\\PortfolioController', 'showMore']);
        $router->get('company', ['App\\Controller\\Site\\CompanyController', 'showCompanyPage']);
        $router->get('delivery', ['App\\Controller\\Site\\DeliveryController', 'showDeliveryPage']);
        $router->get('contacts', ['App\\Controller\\Site\\ContactController', 'index']);
        $router->get('comments', ['App\\Controller\\Site\\CommentController', 'index']);
        $router->get('comments/show-more/{count}', ['App\\Controller\\Site\\CommentController', 'showMore']);
        $router->post('comments/create-comment', ['App\\Controller\\Site\\CommentController', 'createComment']);
        $router->get('blog', ['App\\Controller\\Site\\BlogController', 'index']);
        $router->get('blog/show-more/{count}', ['App\\Controller\\Site\\BlogController', 'showMore']);
        $router->get('blog/{alias}/{id}', ['App\\Controller\\Site\\BlogController', 'showOne']);
        $router->post('search', ['App\\Controller\\Site\\SearchController', 'actionIndex']);

        $router->get('new', ['App\\Controller\\Site\\NewController', 'index']);
        $router->get('new/show-more/{count}', ['App\\Controller\\Site\\NewController', 'showMore']);
        $router->get('new/{alias}/{id}', ['App\\Controller\\Site\\NewController', 'showOne']);

        $router->get('catalog/{alias}/{id}', ['App\\Controller\\Site\\CatalogController', 'showCatalogPage']);
        $router->get('catalog/show-more/{categoryId}/{count}', ['App\\Controller\\Site\\CatalogController', 'getMoreByCategory']);
        $router->get('catalog/comparison-products', ['App\\Controller\\Site\\CatalogController', 'showProductComparisonPage']);
        $router->post('price-list/order', ['App\\Controller\\Site\\PriceListController', 'sendPriceListToClient']);

        $router->get('product/{alias}/{id}', ['App\\Controller\\Site\\ProductController', 'showProductPage']);
        $router->post('product/add-to-comparison', ['App\\Controller\\Site\\ProductController', 'addToComparison']);
        $router->post('product/delete-from-comparison', ['App\\Controller\\Site\\ProductController', 'deleteFromComparison']);

        $router->get('user/login', ['App\\Controller\\Site\\UserController', 'showLoginPage']);
        $router->post('user/login', ['App\\Controller\\Site\\UserController', 'actionLogin']);
        $router->get('user/logout', ['App\\Controller\\Site\\UserController', 'actionLogout']);
        $router->get('user/restore-password', ['App\\Controller\\Site\\UserController', 'showRestorePasswordPage']);
        $router->post('user/restore-password', ['App\\Controller\\Site\\UserController', 'restorePassword']);
        $router->get('user/update-password/{activeHex}', ['App\\Controller\\Site\\UserController', 'showUpdatePasswordPage']);
        $router->post('user/update-password', ['App\\Controller\\Site\\UserController', 'updatePassword']);

        // Страница уведомелний о cookie
        $router->get('cookie', ['App\\Controller\\site\\CookieController', 'showCookiePage']);


        // Главная страница админки
        $router->get('admin', ['App\\Controller\\Admin\\IndexController', 'actionIndex']);

        // Язык
        $router->any('admin/language', [LanguagesController::class, 'actionIndex']);
        $router->get('admin/language/create', [LanguagesController::class, 'actionShowCreatePage']);
        $router->post('admin/language/create', [LanguagesController::class, 'create']);
        $router->get('admin/language/update/{id}', [LanguagesController::class, 'actionShowUpdatePage']);
        $router->post('admin/language/update/{id}', [LanguagesController::class, 'update']);
        $router->get('admin/language/delete/{id}', [LanguagesController::class, 'actionShowDeletePage']);
        $router->post('admin/language/delete', [LanguagesController::class, 'delete']);

        // Страницы категорий
        $router->any('admin/product', [ProductController::class, 'actionIndex']);
        $router->get('admin/product/create', ['App\\Controller\\Admin\\ProductController', 'actionShowCreatePage']);
        $router->post('admin/product/create', ['App\\Controller\\Admin\\ProductController', 'create']);
        $router->get('admin/product/update/{id}', ['App\\Controller\\Admin\\ProductController', 'actionShowUpdatePage']);
        $router->post('admin/product/update/{id}', ['App\\Controller\\Admin\\ProductController', 'update']);
        $router->get('admin/product/delete/{id}', ['App\\Controller\\Admin\\ProductController', 'actionShowDeletePage']);
        $router->post('admin/product/delete', ['App\\Controller\\Admin\\ProductController', 'delete']);
        $router->get('admin/product/photo/delete/{id}/{photoId}', ['App\\Controller\\Admin\\ProductController', 'photoDelete']);

        // Страницы товаров
        $router->any('admin/category', ['App\\Controller\\Admin\\CategoryController', 'actionIndex']);
        $router->get('admin/category/create', ['App\\Controller\\Admin\\CategoryController', 'actionShowCreatePage']);
        $router->post('admin/category/create', ['App\\Controller\\Admin\\CategoryController', 'createCategory']);
        $router->get('admin/category/update/{id}', ['App\\Controller\\Admin\\CategoryController', 'actionShowUpdatePage']);
        $router->post('admin/category/update/{id}', ['App\\Controller\\Admin\\CategoryController', 'updateCategory']);
        $router->get('admin/category/delete/{id}', ['App\\Controller\\Admin\\CategoryController', 'actionShowDeletePage']);
        $router->post('admin/category/delete/{id}', ['App\\Controller\\Admin\\CategoryController', 'deleteCategory']);
        $router->get('admin/category/photo/delete/{id}/{photoId}', ['App\\Controller\\Admin\\CategoryController', 'photoDelete']);

        // Страницы новостей
        $router->any('admin/blog', ['App\\Controller\\Admin\\BlogController', 'actionIndex']);
        $router->get('admin/blog/create', ['App\\Controller\\Admin\\BlogController', 'actionShowCreatePage']);
        $router->post('admin/blog/create', ['App\\Controller\\Admin\\BlogController', 'create']);
        $router->get('admin/blog/update/{id}', ['App\\Controller\\Admin\\BlogController', 'actionShowUpdatePage']);
        $router->post('admin/blog/update/{id}', ['App\\Controller\\Admin\\BlogController', 'update']);
        $router->get('admin/blog/delete/{id}', ['App\\Controller\\Admin\\BlogController', 'actionShowDeletePage']);
        $router->post('admin/blog/delete/{id}', ['App\\Controller\\Admin\\BlogController', 'delete']);

        // Страницы новостей
        $router->any('admin/new', ['App\\Controller\\Admin\\NewController', 'actionIndex']);
        $router->get('admin/new/create', ['App\\Controller\\Admin\\NewController', 'actionShowCreatePage']);
        $router->post('admin/new/create', ['App\\Controller\\Admin\\NewController', 'create']);
        $router->get('admin/new/update/{id}', ['App\\Controller\\Admin\\NewController', 'actionShowUpdatePage']);
        $router->post('admin/new/update/{id}', ['App\\Controller\\Admin\\NewController', 'update']);
        $router->get('admin/new/delete/{id}', ['App\\Controller\\Admin\\NewController', 'actionShowDeletePage']);
        $router->post('admin/new/delete/{id}', ['App\\Controller\\Admin\\NewController', 'delete']);


        // Страницы контента для остальных страниц
        $router->any('admin/page', ['App\\Controller\\Admin\\PageController', 'actionIndex']);
        $router->get('admin/page/update/{id}', ['App\\Controller\\Admin\\PageController', 'actionShowUpdatePage']);
        $router->post('admin/page/update/{id}', ['App\\Controller\\Admin\\PageController', 'update']);

        // Страницы портфолио
        $router->any('admin/portfolio', ['App\\Controller\\Admin\\PortfolioController', 'actionIndex']);
        $router->get('admin/portfolio/create', ['App\\Controller\\Admin\\PortfolioController', 'actionShowCreatePage']);
        $router->post('admin/portfolio/create', ['App\\Controller\\Admin\\PortfolioController', 'create']);
        $router->get('admin/portfolio/update/{id}', ['App\\Controller\\Admin\\PortfolioController', 'actionShowUpdatePage']);
        $router->post('admin/portfolio/update/{id}', ['App\\Controller\\Admin\\PortfolioController', 'update']);
        $router->get('admin/portfolio/delete/{id}', ['App\\Controller\\Admin\\PortfolioController', 'actionShowDeletePage']);
        $router->post('admin/portfolio/delete/{id}', ['App\\Controller\\Admin\\PortfolioController', 'delete']);

        // Страницы отзывов
        $router->any('admin/comments', ['App\\Controller\\Admin\\CommentController', 'actionIndex']);
        $router->get('admin/comments/publish/{id}', ['App\\Controller\\Admin\\CommentController', 'publish']);
        $router->get('admin/comments/create/{id}', ['App\\Controller\\Admin\\CommentController', 'actionShowCreatePage']);
        $router->post('admin/comments/create/{id}', ['App\\Controller\\Admin\\CommentController', 'create']);
        $router->get('admin/comments/delete/{id}', ['App\\Controller\\Admin\\CommentController', 'actionShowDeletePage']);
        $router->post('admin/comments/delete/{id}', ['App\\Controller\\Admin\\CommentController', 'delete']);

        $router->any('admin/user', ['App\\Controller\\Admin\\UserController', 'actionIndex']);
        $router->get('admin/user/create', ['App\\Controller\\Admin\\UserController', 'actionShowCreatePage']);
        $router->post('admin/user/create', ['App\\Controller\\Admin\\UserController', 'create']);
        $router->get('admin/user/update/{id}', ['App\\Controller\\Admin\\UserController', 'actionShowUpdatePage']);
        $router->post('admin/user/update/{id}', ['App\\Controller\\Admin\\UserController', 'update']);
        $router->get('admin/user/delete/{id}', ['App\\Controller\\Admin\\UserController', 'actionShowDeletePage']);
        $router->post('admin/user/delete/{id}', ['App\\Controller\\Admin\\UserController', 'delete']);


        $router->any('admin/price-list', ['App\\Controller\\Admin\\PriceListController', 'actionIndex']);
        $router->get('admin/price-list/create', ['App\\Controller\\Admin\\PriceListController', 'actionShowCreatePage']);
        $router->post('admin/price-list/create', ['App\\Controller\\Admin\\PriceListController', 'create']);
        $router->get('admin/price-list/update/{id}', ['App\\Controller\\Admin\\PriceListController', 'actionShowUpdatePage']);
        $router->post('admin/price-list/update/{id}', ['App\\Controller\\Admin\\PriceListController', 'update']);
        $router->get('admin/price-list/delete/{id}', ['App\\Controller\\Admin\\PriceListController', 'actionShowDeletePage']);
        $router->post('admin/price-list/delete/{id}', ['App\\Controller\\Admin\\PriceListController', 'delete']);
        $router->any('admin/price-list-order', ['App\\Controller\\Admin\\PriceListController', 'actionOrder']);
    }
}
