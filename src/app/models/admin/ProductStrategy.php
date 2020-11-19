<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductDescriptionRepository;
use App\Repository\ProductRecommendationsRepository;
use App\Repository\ProductRepository;

class ProductStrategy extends AbstractAdminModel
{
    public $fileDirectory = 'product';

    public function getIndexData($order = null)
    {
        $repository = new ProductRepository();
        $data['productList'] = $repository->getAll($order);

        return $data;
    }

    public function getShowCreatePageData($order = null)
    {
        $productRepository = new ProductRepository();
        $data['productList'] = $productRepository->getAll();

        $productRepository = new CategoryRepository();
        $productList = $productRepository->getAll();

        // удаляем возможность добавления товара в категорию у которой есть дочерняя
        foreach ($productList as $key => $category) {
            $childCategory = $productRepository->getChildCategoryListById($category['id']);
            if (!empty($childCategory)) {
                unset($productList[$key]);
            }
        }

        $data['categoryList'] = $productList;

        return $data;
    }

    public function validation($file, $params)
    {

    }

    public function create($data)
    {
        $repository = new ProductRepository();
        $newProductId = $repository->create($data);

        $productDescriptionRepository = new ProductDescriptionRepository();

        foreach ($data['description'] as $description){
            $productDescriptionRepository->create($newProductId, $description);
        }

        $productRecommendationsRepository = new ProductRecommendationsRepository();
        if (!empty($params['recommendation_ids'])) {
            foreach ($params['recommendation_ids'] as $recommendation) {
                $productRecommendationsRepository->create($newProductId, $recommendation);
            }
        }

        return $newProductId;
    }

    public function getShowUpdatePageData($id)
    {
        $productRepository = new ProductRepository();
        $product = $productRepository->getById($id);

        $productRepository = new ProductDescriptionRepository();
        $productDescription = $productRepository->getById($product['id']);

        foreach ($productDescription as $description) {
            $product['language_id'][$description['language_id']] = $description;
        }


        $categoryRepository = new CategoryRepository();
        $categoryList = $categoryRepository->getAll();

        $productRecommendationsRepository = new ProductRecommendationsRepository();
        $productRecommendations = $productRecommendationsRepository->getProductRecommendationsIdsByProductId($id);

        $productRecommendationIds = [];

        foreach ($productRecommendations as $product) {
            $productRecommendationIds[] = $product['id'];
        }

        $productList = $productRepository->getAll();
        foreach ($productList as $key => $product) {
            $productList[$key]['selected'] = in_array($product['id'], $productRecommendationIds);
        }

        $data['product'] = $product;
        $data['productList'] = $productList;
        $data['categoryList'] = $categoryList;
        $data['productFilesList'] = $productRepository->getProductFilesByProductId($id);

        return $data;
    }

    public function update($data)
    {
        $repository = new ProductRepository();
        $newCategory = $repository->updateById($data);

        if (empty($newCategory)) {
            $res['errors'][] = 'Ошибка сохранения товара';
            return $res;
        }

        $productRecommendationsRepository = new ProductRecommendationsRepository();
        $productRecommendations = $productRecommendationsRepository->getProductRecommendationsIdsByProductId($params['id']);

        foreach ($productRecommendations as $product) {
            $productRecommendationsRepository->deleteProductRecommendations($product['id'], $params['id']);
        }

        if (!empty($params['recommendation_ids'])) {
            foreach ($params['recommendation_ids'] as $recommendation) {
                $productRecommendation = $productRecommendationsRepository->create($params['id'],
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
        $repository = new ProductRepository();
        $data['product'] = $repository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $repository = new ProductRepository();
        return $repository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
        $repository = new ProductRepository();
        return $repository->createFilesConnection($id, $fileId);
    }

    public function deleteFileConnection($id, $photoId): bool
    {
        $categoryRepository = new ProductRepository();

        if ($categoryRepository->deleteFileConnection($id, $photoId)) {
            return true;
        }

        return false;
    }

    public function prepareData($params)
    {
        $languageRepository = new LanguageRepository();
        $languages = $languageRepository->getAll();

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
}

