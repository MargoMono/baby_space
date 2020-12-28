<?php

namespace App\Components;

class Cart
{
    public static function add($params)
    {
        if (empty($_SESSION['cart'])) {
            $_SESSION['cart'][$params['id']] = $params['count'];
            return;
        }

        if (empty($_SESSION['cart'][$params['id']])) {
            $_SESSION['cart'][$params['id']] = $params['count'];
            return;
        }

        $_SESSION['cart'][$params['id']] += $params['count'];
    }

    public static function update($params)
    {
        if (empty($_SESSION['cart'])) {
            return;
        }

        $_SESSION['cart'][$params['id']] = $params['count'];
    }

    public static function delete($params)
    {
        if (empty($_SESSION['cart'])) {
            return;
        }

        unset($_SESSION['cart'][$params['id']]);
    }

    public static function getAllCartData()
    {
        return $_SESSION['cart'];
    }

    public static function getProductCountById($id)
    {
        return $_SESSION['cart'][$id];
    }

    public static function getProductCountInCart()
    {
        $productCountInCart = 0;

        if (empty($_SESSION['cart'])) {
            return $productCountInCart;
        }

        foreach ($_SESSION['cart'] as $id => $count) {
            $productCountInCart += $count;
        }

        return $productCountInCart;
    }

    public static function addCoupon($id)
    {
        $_SESSION['coupon'] = $id;
    }
}

