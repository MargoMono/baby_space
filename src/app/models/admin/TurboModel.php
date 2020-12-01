<?php

namespace App\Models\Admin;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use DOMDocument;

class TurboModel
{
    public function getIndexData()
    {
        $dom = new domDocument('1.0', 'utf-8');

        $ymlCatalogItem = $dom->createElement('yml_catalog');
        $dom->appendChild($ymlCatalogItem);
        $ymlCatalogItem->setAttribute('date', date('Y-m-d H:i'));

        $shopItem = $dom->createElement('shop');

        $name = $dom->createElement('name', 'BestSeller');
        $shopItem->appendChild($name);

        $company = $dom->createElement('company', 'Tne Best inc.');
        $shopItem->appendChild($company);

        $url = $dom->createElement('url', 'http://best.seller.ru');
        $shopItem->appendChild($url);

        $currencies = $dom->createElement('currencies');
        $shopItem->appendChild($currencies);

        $currency = $dom->createElement('currency');
        $currency->setAttribute('id', 'RUR');
        $currency->setAttribute('rate', '1');
        $currencies->appendChild($currency);

        $categories = $dom->createElement('categories');

        $categoryRepository = new CategoryRepository();
        $categoryList = $categoryRepository->getAll();

        $shopItem->appendChild($categories);

        foreach ($categoryList as $category) {
            $categoryItem = $dom->createElement('category', $category['name']);
            $categoryItem->setAttribute('id', $category['id']);
            $categories->appendChild($categoryItem);
        }

        $deliveryOptions = $dom->createElement('delivery-options');
        $deliveryOptions->setAttribute('cost', 200);
        $deliveryOptions->setAttribute('days', 1);
        $shopItem->appendChild($deliveryOptions);

        $offers = $dom->createElement('offers');
        $shopItem->appendChild($offers);

        $productRepository = new ProductRepository();
        $productList = $productRepository->getAll();

        foreach ($productList as $product) {
            $offer = $dom->createElement('offer');
            $offer->setAttribute('id', $product['id']);

            $name = $dom->createElement('name', $product['product_name']);
            $offer->appendChild($name);

            $url = $dom->createElement('url', $product['alias']);
            $offer->appendChild($url);

            $price = $dom->createElement('price', $product['price']);
            $offer->appendChild($price);

            $currencyId = $dom->createElement('currencyId', 'RUR');
            $offer->appendChild($currencyId);

            $categoryId = $dom->createElement('categoryId', $product['category_id']);
            $offer->appendChild($categoryId);

            $offers->appendChild($offer);
        }

        $ymlCatalogItem->appendChild($shopItem);

        $dom->save('users.xml');
    }
}

