<?php

namespace App\Controllers\Site;

use App\Components\Cart;
use App\Models\Site\CartModel;
use LapayGroup\RussianPost\TariffCalculation;

class CartController
{
    private $directory = 'cart';
    private $controllerContext;
    private $model;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new CartModel();
    }

    public function actionIndex()
    {
        $data = $this->model->getIndexData();
        $this->controllerContext->render($data, 'index.twig');
    }

    public function actionAdd()
    {
        Cart::add($_POST);
        $this->controllerContext->generateAjax([
            'count' => Cart::getProductCountInCart()
        ]);
    }

    public function actionUpdate()
    {
        Cart::update($_POST);

        $data = $this->model->getUpdateData($_POST['id']);
        $this->controllerContext->generateAjax($data);
    }

    public function actionDelete()
    {
        Cart::delete($_POST);

        $data = $this->model->getUpdateData($_POST['id']);
        $this->controllerContext->generateAjax($data);
    }

    public function actionCouponAdd()
    {
        $data = $this->model->getCouponData($_POST['coupon']);

        if ($data) {
            Cart::addCoupon($data['id']);
        }

        $this->controllerContext->generateAjax($data);
    }

    public function actionCalculateDelivery()
    {
        $data = $this->model->getCalculateDeliveryData($_POST);
        $this->controllerContext->generateAjax($data);
    }
}