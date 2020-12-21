<?php

namespace App\Components;

class Cart
{
    public static function setCartData($params)
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

    public static function getAllCartData()
    {
        return $_SESSION['cart'];
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
}

