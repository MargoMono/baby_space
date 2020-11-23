<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductDescriptionRepository;
use App\Repository\ProductRecommendationsRepository;
use App\Repository\ProductRepository;

class ProductModel implements ModelStrategy
{
    public $fileDirectory = 'product';
    public $productRepository;
    public $categoryRepository;
    public $productDescriptionRepository;
    public $productRecommendationsRepository;
    public $languageRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->productDescriptionRepository = new ProductDescriptionRepository();
        $this->productRecommendationsRepository = new ProductRecommendationsRepository();
        $this->languageRepository = new LanguageRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $productList = $this->productRepository->getAll($sort);
        $data['productList'] = $productList;

        if($sort['desc'] == 'DESC'){
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;

        return $data;
    }

    public function getFilteredData($data)
    {
        var_dump($data);
    }

    public function getShowCreatePageData($sort = null)
    {
        $categoryList = $this->categoryRepository->getAll();

        // удаляем возможность добавления товара в категорию у которой есть дочерняя
        foreach ($categoryList as $key => $category) {
            $childCategory = $this->categoryRepository->getChildCategoryListById($category['id']);
            if (!empty($childCategory)) {
                unset($categoryList[$key]);
            }
        }

        $data['categoryList'] = $categoryList;
        $data['languages'] = $this->languageRepository->getAll();

        return $data;
    }

    public function create($data)
    {
        $newProductId = $this->productRepository->create($data);

        foreach ($data['description'] as $description) {
            $this->productDescriptionRepository->create($newProductId, $description);
        }

        if (!empty($params['recommendation_ids'])) {
            foreach ($params['recommendation_ids'] as $recommendation) {
                $this->productRecommendationsRepository->create($newProductId, $recommendation);
            }
        }

        return $newProductId;
    }

    public function getShowUpdatePageData($id)
    {
        $product = $this->productRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['product'] = $this->productDescriptionRepository->getByIdAndLanguageId($product['id'],
                $language['id']);
        }

        $data['languages'] = $languages;
        $data['categoryList'] = $this->categoryRepository->getAll();;
        $data['productFilesList'] = $this->productRepository->getProductFilesByProductId($id);

        return $data;
    }

    public function update($file, $data)
    {
        $newCategory = $this->productRepository->updateById($data);

        if (empty($newCategory)) {
            $res['errors'][] = 'Ошибка сохранения товара';
            return $res;
        }

        $productRecommendations = $this->productRecommendationsRepository->getProductRecommendationsIdsByProductId($params['id']);

        foreach ($productRecommendations as $product) {
            $this->productRecommendationsRepository->deleteProductRecommendations($product['id'], $params['id']);
        }

        if (!empty($params['recommendation_ids'])) {
            foreach ($params['recommendation_ids'] as $recommendation) {
                $productRecommendation = $this->productRecommendationsRepository->create($params['id'],
                    $recommendation);

                if (empty($productRecommendation)) {
                    $res['errors'][] = 'Ошибка сохранения рекомендаций';
                    return $res;
                }
            }
        }

        return $newCategory;
    }

    public function getShowDeletePageData($id)
    {
        $data['product'] = $this->productRepository->getById($id);

        return $data;
    }

    public function delete($id)
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

    public function prepareData($params)
    {
        $languages = $this->languageRepository->getAll();

        $paramsDescription = [];

        foreach ($languages as $language) {
            $paramsDescription[$language['id']] = [
                'language_id' => $language['id'],
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
            'status' => $params['status'],
            'sort' => $params['sort'],
            'file_id' => $params['file_id'],
            'alias' => TextHelper::getTranslit($params['name']),
            'description' => $paramsDescription,
        ];
    }

    public function validation($file, $params)
    {
        // TODO: Implement validation() method.
    }

    public function getFile($id)
    {
        return $this->productRepository->getById($id);
    }

    public function getFiles($id)
    {
        return $this->productRepository->getProductFilesByProductId($id);
    }
}

