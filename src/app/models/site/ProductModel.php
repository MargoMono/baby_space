<?php

namespace App\Models\Site;

use App\Components\Language;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;

class ProductModel
{
    private $language;
    private $productRepository;

    public function __construct()
    {
        $this->language = (new LanguageRepository())->getByAlias((new Language())->getLanguage());
        $this->productRepository = new ProductRepository();
    }

    public function getIndexData($id = null)
    {
        $product = $this->productRepository->getById(
            $id,
            ['language_id' => $this->language['id']],
        );

        return [
            'product' => $product,
        ];
    }
}

