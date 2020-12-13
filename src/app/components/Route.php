<?php

namespace App\Components;

use App\Models\Model;
use App\View\View;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteCollector;
use App\Controllers\Site\IndexController;
use App\Controllers\Admin;
use App\Controllers\Site\BlogController;
use App\Controllers\Site\ContactController;
use App\Controllers\Site\CatalogController;

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
        $router->get('/', [IndexController::class, 'showHomePage']);
        $router->get('change-language/{id}', [IndexController::class, 'actionChangeLanguage']);

        $router->get('blog', [BlogController::class, 'actionIndex']);
        $router->get('blog/show-more/{count}', [BlogController::class, 'actionShowMore']);
        $router->get('blog/last-page/{count}', [BlogController::class, 'actionLastPage']);
        $router->get('blog/{alias}/{id}', [BlogController::class, 'actionShowSingle']);

        $router->get('contacts', [ContactController::class, 'index']);


        $router->get('catalog', [CatalogController::class, 'actionIndex']);
        $router->get('catalog/show-more/{count}', [CatalogController::class, 'actionShowMore']);
        $router->get('catalog/last-page/{count}', [CatalogController::class, 'actionLastPage']);


        $router->get('company', ['App\\Controllers\\Site\\CompanyController', 'showCompanyPage']);
        $router->get('delivery', ['App\\Controllers\\Site\\DeliveryController', 'showDeliveryPage']);
        $router->get('comments', ['App\\Controllers\\Site\\CommentController', 'index']);
        $router->get('comments/show-more/{count}', ['App\\Controllers\\Site\\CommentController', 'showMore']);
        $router->post('comments/create-comment', ['App\\Controllers\\Site\\CommentController', 'createComment']);

        $router->post('search', ['App\\Controllers\\Site\\SearchController', 'actionIndex']);

        $router->get('new', ['App\\Controllers\\Site\\NewController', 'index']);
        $router->get('new/show-more/{count}', ['App\\Controllers\\Site\\NewController', 'showMore']);
        $router->get('new/{alias}/{id}', ['App\\Controllers\\Site\\NewController', 'showOne']);

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
        $router->get('admin/turbo', [Admin\TurboController::class, 'actionIndex']);

        // Язык
        $router->any('admin/language', [Admin\LanguagesController::class, 'actionIndex']);
        $router->any('admin/language/sort/{id}?', [Admin\LanguagesController::class, 'actionIndex']);
        $router->get('admin/language/create', [Admin\LanguagesController::class, 'actionShowCreatePage']);
        $router->post('admin/language/create', [Admin\LanguagesController::class, 'create']);
        $router->get('admin/language/update/{id}', [Admin\LanguagesController::class, 'actionShowUpdatePage']);
        $router->post('admin/language/update/{id}', [Admin\LanguagesController::class, 'update']);
        $router->get('admin/language/delete/{id}', [Admin\LanguagesController::class, 'actionShowDeletePage']);
        $router->post('admin/language/delete', [Admin\LanguagesController::class, 'delete']);

        // Валюта
        $router->any('admin/currency', [Admin\CurrencyController::class, 'actionIndex']);
        $router->any('admin/currency/sort/{id}?', [Admin\CurrencyController::class, 'actionIndex']);
        $router->get('admin/currency/create', [Admin\CurrencyController::class, 'actionShowCreatePage']);
        $router->post('admin/currency/create', [Admin\CurrencyController::class, 'create']);
        $router->get('admin/currency/update/{id}', [Admin\CurrencyController::class, 'actionShowUpdatePage']);
        $router->post('admin/currency/update/{id}', [Admin\CurrencyController::class, 'update']);
        $router->get('admin/currency/delete/{id}', [Admin\CurrencyController::class, 'actionShowDeletePage']);
        $router->post('admin/currency/delete', [Admin\CurrencyController::class, 'delete']);

        // Страны
        $router->any('admin/country', [Admin\CountryController::class, 'actionIndex']);
        $router->any('admin/country/sort/{id}?', [Admin\CountryController::class, 'actionIndex']);
        $router->get('admin/country/create', [Admin\CountryController::class, 'actionShowCreatePage']);
        $router->post('admin/country/create', [Admin\CountryController::class, 'create']);
        $router->get('admin/country/update/{id}', [Admin\CountryController::class, 'actionShowUpdatePage']);
        $router->post('admin/country/update/{id}', [Admin\CountryController::class, 'update']);
        $router->get('admin/country/delete/{id}', [Admin\CountryController::class, 'actionShowDeletePage']);
        $router->post('admin/country/delete', [Admin\CountryController::class, 'delete']);

        // Заказы
        $router->any('admin/order', [Admin\OrderController::class, 'actionIndex']);
        $router->any('admin/order/sort/{id}?', [Admin\OrderController::class, 'actionIndex']);
        $router->any('admin/order/filter', [Admin\OrderController::class, 'actionFilter']);
        $router->get('admin/order/view/{id}', [Admin\OrderController::class, 'actionShowViewPage']);
        $router->get('admin/order/update/{id}', [Admin\OrderController::class, 'actionShowUpdatePage']);
        $router->post('admin/order/update/{id}', [Admin\OrderController::class, 'update']);
        $router->get('admin/order/delete/{id}', [Admin\OrderController::class, 'actionShowDeletePage']);
        $router->post('admin/order/delete', [Admin\OrderController::class, 'delete']);

        // Страницы товаров
        $router->any('admin/product', [Admin\ProductController::class, 'actionIndex']);
        $router->any('admin/product/sort/{id}?', [Admin\ProductController::class, 'actionIndex']);
        $router->any('admin/product/filter', [Admin\ProductController::class, 'actionFilter']);
        $router->get('admin/product/create', [Admin\ProductController::class, 'actionShowCreatePage']);
        $router->post('admin/product/create', [Admin\ProductController::class, 'create']);
        $router->get('admin/product/update/{id}', [Admin\ProductController::class, 'actionShowUpdatePage']);
        $router->post('admin/product/update/{id}', [Admin\ProductController::class, 'update']);
        $router->get('admin/product/delete/{id}', [Admin\ProductController::class, 'actionShowDeletePage']);
        $router->post('admin/product/delete', [Admin\ProductController::class, 'delete']);
        $router->get('admin/product/image/delete/{id}/{photoId}', [Admin\ProductController::class, 'imageDelete']);


        // Купоны
        $router->any('admin/coupon', [Admin\CouponController::class, 'actionIndex']);
        $router->any('admin/coupon/sort/{id}?', [Admin\CouponController::class, 'actionIndex']);
        $router->get('admin/coupon/create', [Admin\CouponController::class, 'actionShowCreatePage']);
        $router->post('admin/coupon/create', [Admin\CouponController::class, 'create']);
        $router->get('admin/coupon/update/{id}', [Admin\CouponController::class, 'actionShowUpdatePage']);
        $router->post('admin/coupon/update/{id}', [Admin\CouponController::class, 'update']);
        $router->get('admin/coupon/delete/{id}', [Admin\CouponController::class, 'actionShowDeletePage']);
        $router->post('admin/coupon/delete', [Admin\CouponController::class, 'delete']);


        // Купоны
        $router->get('admin/sale/update/{id}', [Admin\SaleController::class, 'actionShowUpdatePage']);
        $router->post('admin/sale/update/{id}', [Admin\SaleController::class, 'update']);

        // Страницы категорий
        $router->any('admin/category', [Admin\CategoryController::class, 'actionIndex']);
        $router->any('admin/category/sort/{id}?', [Admin\CategoryController::class, 'actionIndex']);
        $router->get('admin/category/create', [Admin\CategoryController::class, 'actionShowCreatePage']);
        $router->post('admin/category/create', [Admin\CategoryController::class, 'create']);
        $router->get('admin/category/update/{id}', [Admin\CategoryController::class, 'actionShowUpdatePage']);
        $router->post('admin/category/update/{id}', [Admin\CategoryController::class, 'update']);
        $router->get('admin/category/delete/{id}', [Admin\CategoryController::class, 'actionShowDeletePage']);
        $router->post('admin/category/delete', [Admin\CategoryController::class, 'delete']);

        // Страницы размеров
        $router->any('admin/size', [Admin\SizeController::class, 'actionIndex']);
        $router->any('admin/size/sort/{id}?', [Admin\SizeController::class, 'actionIndex']);
        $router->get('admin/size/create', [Admin\SizeController::class, 'actionShowCreatePage']);
        $router->post('admin/size/create', [Admin\SizeController::class, 'create']);
        $router->get('admin/size/update/{id}', [Admin\SizeController::class, 'actionShowUpdatePage']);
        $router->post('admin/size/update/{id}', [Admin\SizeController::class, 'update']);
        $router->get('admin/size/delete/{id}', [Admin\SizeController::class, 'actionShowDeletePage']);
        $router->post('admin/size/delete', [Admin\SizeController::class, 'delete']);

        // Блог
        $router->any('admin/blog', [Admin\BlogController::class, 'actionIndex']);
        $router->any('admin/blog/sort/{id}?', [Admin\BlogController::class, 'actionIndex']);
        $router->get('admin/blog/create', [Admin\BlogController::class, 'actionShowCreatePage']);
        $router->post('admin/blog/create', [Admin\BlogController::class, 'create']);
        $router->get('admin/blog/update/{id}', [Admin\BlogController::class, 'actionShowUpdatePage']);
        $router->post('admin/blog/update/{id}', [Admin\BlogController::class, 'update']);
        $router->get('admin/blog/delete/{id}', [Admin\BlogController::class, 'actionShowDeletePage']);
        $router->post('admin/blog/delete', [Admin\BlogController::class, 'delete']);

        // Страницы новостей
        $router->any('admin/new', [Admin\NewController::class, 'actionIndex']);
        $router->any('admin/new/sort/{id}?', [Admin\NewController::class, 'actionIndex']);
        $router->get('admin/new/create', [Admin\NewController::class, 'actionShowCreatePage']);
        $router->post('admin/new/create', [Admin\NewController::class, 'create']);
        $router->get('admin/new/update/{id}', [Admin\NewController::class, 'actionShowUpdatePage']);
        $router->post('admin/new/update/{id}', [Admin\NewController::class, 'update']);
        $router->get('admin/new/delete/{id}', [Admin\NewController::class, 'actionShowDeletePage']);
        $router->post('admin/new/delete', [Admin\NewController::class, 'delete']);

        // Страницы отзывов
        $router->any('admin/comment', [Admin\CommentController::class, 'actionIndex']);
        $router->any('admin/comment/sort/{id}?', [Admin\CommentController::class, 'actionIndex']);
        $router->get('admin/comment/publish/{id}', [Admin\CommentController::class, 'publish']);
        $router->get('admin/comment/create', [Admin\CommentController::class, 'actionShowCreatePage']);
        $router->post('admin/comment/create', [Admin\CommentController::class, 'create']);
        $router->get('admin/comment/update/{id}', [Admin\CommentController::class, 'actionShowUpdatePage']);
        $router->post('admin/comment/update/{id}', [Admin\CommentController::class, 'update']);
        $router->get('admin/comment/delete/{id}', [Admin\CommentController::class, 'actionShowDeletePage']);
        $router->post('admin/comment/delete', [Admin\CommentController::class, 'delete']);
        $router->get('admin/comment/image/delete/{id}/{photoId}', [Admin\CommentController::class, 'imageDelete']);
        $router->get('admin/comment-answer/image/delete/{commentId}/{commentAnswerId}/{photoId}', [Admin\CommentController::class, 'commentAnswerImageDelete']);


        $router->any('admin/user', [Admin\UserController::class, 'actionIndex']);
        $router->any('admin/user/sort/{id}?', [Admin\UserController::class, 'actionIndex']);
        $router->get('admin/user/create', [Admin\UserController::class, 'actionShowCreatePage']);
        $router->post('admin/user/create', [Admin\UserController::class, 'create']);
        $router->get('admin/user/update/{id}', [Admin\UserController::class, 'actionShowUpdatePage']);
        $router->post('admin/user/update/{id}', [Admin\UserController::class, 'update']);
        $router->get('admin/user/delete/{id}', [Admin\UserController::class, 'actionShowDeletePage']);
        $router->post('admin/user/delete', [Admin\UserController::class, 'delete']);
    }
}

