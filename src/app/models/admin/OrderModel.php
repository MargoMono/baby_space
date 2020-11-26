<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductDescriptionRepository;
use App\Repository\ProductRecommendationsRepository;

class OrderModel implements ModelStrategy
{
    public $fileDirectory = 'order';
    public $orderRepository;
    public $categoryRepository;
    public $productDescriptionRepository;
    public $productRecommendationsRepository;
    public $languageRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
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
        $productList = $this->orderRepository->getAll($sort);
        $data['productList'] = $productList;

        if($sort['desc'] == 'DESC'){
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;

        $data['categoryList'] = $this->categoryRepository->getAll();

        return $data;
    }

    public function getFilteredData($data)
    {
        return $this->orderRepository->getFilteredData($data);
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
        $data['productRecommendationList'] = $this->orderRepository->getAll();

        return $data;
    }

    public function create($data)
    {
        $newProductId = $this->orderRepository->create($data);

        foreach ($data['description'] as $description) {
            $this->productDescriptionRepository->create($newProductId, $description);
        }

        if (!empty($data['product_recommendation'])) {
            foreach ($data['product_recommendation'] as $recommendation) {
                $this->productRecommendationsRepository->create($newProductId, $recommendation);
            }
        }

        return $newProductId;
    }

    public function getShowUpdatePageData($id)
    {
        $product = $this->orderRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['product'] = $this->productDescriptionRepository->getByIdAndLanguageId($product['id'],
                $language['id']);
        }

        $data['product'] = $product;
        $data['languages'] = $languages;

        $data['categoryList'] = $this->categoryRepository->getAll();
        $data['productFilesList'] = $this->orderRepository->getProductFilesByProductId($id);
        $data['productRecommendationList'] = $this->orderRepository->getRecomendations($id);
        $data['productRecommendationListActual'] = $this->productRecommendationsRepository->getProductRecommendationsIdsByProductId($id);

        return $data;
    }

    public function update($file, $data)
    {
        $this->orderRepository->updateById($data);

        foreach ($data['description'] as $productDescription){
            $this->productDescriptionRepository->updateById($productDescription);
        }

        $this->productRecommendationsRepository->deleteByProductId($data['id']);

        if (!empty($data['product_recommendation'])) {
            foreach ($data['product_recommendation'] as $recommendation) {
                $this->productRecommendationsRepository->create($data['id'], $recommendation);
            }
        }
    }

    public function getShowDeletePageData($id)
    {
        $data['product'] = $this->orderRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $this->orderRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
        $this->orderRepository->createFilesConnection($id, $fileId);
    }

    public function deleteFileConnection($id, $imageId)
    {
        $this->orderRepository->deleteFileConnection($id, $imageId);
    }

    public function getFile($id)
    {
        return $this->orderRepository->getFile($id);
    }

    public function getFiles($id)
    {
        return $this->orderRepository->getFiles($id);
    }

    public function prepareData($params)
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
            'status' => $params['status'],
            'sort' => $params['sort'],
            'file_id' => $params['file_id'],
            'alias' => TextHelper::getTranslit($params['name-1']),
            'description' => $paramsDescription,
            'product_recommendation' => $params['product_recommendation'],
        ];
    }

    public function validation($file, $params)
    {
        // TODO: Implement validation() method.
    }


}

