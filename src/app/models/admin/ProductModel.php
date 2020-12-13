<?php

namespace App\Models\Admin;

use App\Helpers\CalculationHelper;
use App\Helpers\TextHelper;
use App\Repository\CategoryRepository;
use App\Repository\CountryRepository;
use App\Repository\CurrencyRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductCountryRepository;
use App\Repository\ProductDescriptionRepository;
use App\Repository\ProductRecommendationsRepository;
use App\Repository\ProductRepository;
use App\Repository\SizeRepository;
use App\Repository\TypeRepository;

class ProductModel implements ModelStrategy
{
    public $fileDirectory = 'product';
    public $productRepository;
    public $categoryRepository;
    public $sizeRepository;
    public $typeRepository;
    public $productDescriptionRepository;
    public $productRecommendationsRepository;
    public $languageRepository;
    public $countryRepository;
    public $currencyRepository;
    public $productCountryRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->sizeRepository = new SizeRepository();
        $this->typeRepository = new TypeRepository();
        $this->languageRepository = new LanguageRepository();
        $this->countryRepository = new CountryRepository();
        $this->currencyRepository = new CurrencyRepository();
        $this->productDescriptionRepository = new ProductDescriptionRepository();
        $this->productRecommendationsRepository = new ProductRecommendationsRepository();
        $this->productCountryRepository = new ProductCountryRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null): array
    {
        $productList = $this->productRepository->getAll($sort);

        if ($sort['desc'] == 'DESC') {
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;

        $data['categoryList'] = $this->categoryRepository->getAll();

        foreach ($productList as $key => $product) {
            $salePrice = null;

            if (!empty($product['sale'])) {
                $salePrice = CalculationHelper::sale($product['price'], $product['sale']);
            }

            foreach ($this->currencyRepository->getAllCurrencyForConvert() as $currency) {
                $productList[$key]['convert'][$currency['code']] = CalculationHelper::convert($product['price'],
                    $currency['rate']);

                if (empty($salePrice)) {
                    continue;
                }

                $productList[$key]['convert_sale'][$currency['code']] = CalculationHelper::convert($salePrice,
                    $currency['rate']);
                $productList[$key]['sale_price'] = $salePrice;
            }
        }

        $data['productList'] = $productList;

        return $data;
    }

    public function getShowCreatePageData($sort = null): array
    {
        $data['categoryList'] = $this->categoryRepository->getAll();
        $data['sizeList'] = $this->sizeRepository->getAll();
        $data['typeList'] = $this->typeRepository->getAll();
        $data['countryList'] = $this->countryRepository->getAll();
        $data['languages'] = $this->languageRepository->getAll();
        $data['productRecommendationList'] = $this->productRepository->getAll();

        return $data;
    }

    public function create($data): int
    {
        $newProductId = $this->productRepository->create($data);

        foreach ($data['description'] as $description) {
            $this->productDescriptionRepository->create($newProductId, $description);
        }

        if (!empty($data['product_recommendation'])) {
            foreach ($data['product_recommendation'] as $country) {
                $this->productRecommendationsRepository->create($newProductId, $country);
            }
        }

        if (!empty($data['product_country'])) {
            foreach ($data['product_country'] as $country) {
                $this->productCountryRepository->create($newProductId, $country);
            }
        }

        return $newProductId;
    }

    public function getShowUpdatePageData($id): array
    {
        $product = $this->productRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['product'] = $this->productDescriptionRepository->getByIdAndLanguageId($product['id'],
                $language['id']);
        }

        $data['product'] = $product;
        $data['languages'] = $languages;

        $data['categoryList'] = $this->categoryRepository->getAll();
        $data['sizeList'] = $this->sizeRepository->getAll();
        $data['typeList'] = $this->typeRepository->getAll();
        $data['productFilesList'] = $this->productRepository->getProductFilesByProductId($id);
        $data['productRecommendationList'] = $this->productRepository->getRecomendations($id);
        $data['productRecommendationListActual'] = $this->productRecommendationsRepository->getProductRecommendationsIdsByProductId($id);

        $data['countryList'] = $this->countryRepository->getAll();
        $data['countryListActual'] = $this->productCountryRepository->getProductCountryIdsByProductId($id);

        return $data;
    }

    public function update($file, $data): void
    {
        $this->productRepository->updateById($data);

        foreach ($data['description'] as $productDescription) {
            $productDescriptionExist = $this->productDescriptionRepository->getByIdAndLanguageId($data['id'],
                $productDescription['language_id']);

            if (empty($productDescriptionExist)) {
                $this->productDescriptionRepository->create($data['id'], $productDescription);
            } else {
                $this->productDescriptionRepository->updateById($productDescription);
            }
        }

        $this->productRecommendationsRepository->deleteByProductId($data['id']);

        if (!empty($data['product_recommendation'])) {
            foreach ($data['product_recommendation'] as $recommendation) {
                $this->productRecommendationsRepository->create($data['id'], $recommendation);
            }
        }

        $this->productCountryRepository->deleteByProductId($data['id']);

        if (!empty($data['product_country'])) {
            foreach ($data['product_country'] as $country) {
                $this->productCountryRepository->create($data['id'], $country);
            }
        }
    }

    public function getShowDeletePageData($id): array
    {
        $data['product'] = $this->productRepository->getById($id);

        return $data;
    }

    public function delete($id): void
    {
        $this->productRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
        $this->productRepository->createFilesConnection($id, $fileId);
    }

    public function deleteFileConnection($id, $imageId)
    {
        $this->productRepository->deleteFileConnection($id, $imageId);
    }

    public function getFile($id)
    {
        return $this->productRepository->getFile($id);
    }

    public function getFiles($id)
    {
        return $this->productRepository->getFiles($id);
    }

    public function getFilteredData($data)
    {
        return $this->productRepository->getFilteredData($data);
    }

    public function prepareData($params): array
    {
        $languages = $this->languageRepository->getAll();

        $paramsDescription = [];

        foreach ($languages as $language) {
            $paramsDescription[$language['id']] = [
                'language_id' => $language['id'],
                'id' => $params['id-' . $language['id']],
                'name' => $params['name-' . $language['id']],
                'description' => $params['description-' . $language['id']],
                'meta_title' => $params['meta_title-' . $language['id']],
                'meta_description' => $params['meta_description-' . $language['id']],
                'meta_keyword' => $params['meta_keyword-' . $language['id']],
                'tag' => $params['tag-' . $language['id']],
            ];
        }

        return [
            'id' => $params['id'],
            'category_id' => $params['category_id'],
            'size_id' => $params['size_id'],
            'type_id' => $params['type_id'],
            'price' => $params['price'],
            'sale' => $params['sale'],
            'status' => $params['status'],
            'popular' => $params['popular'],
            'sort' => $params['sort'],
            'file_id' => $params['file_id'],
            'alias' => TextHelper::getTranslit($params['name-1']),
            'description' => $paramsDescription,
            'product_recommendation' => $params['product_recommendation'],
            'product_country' => $params['product_country'],
        ];
    }
}

