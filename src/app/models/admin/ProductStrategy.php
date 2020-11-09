<?php

namespace App\Model\Admin;

use App\Helper\TextHelper;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\CoatingRepository;
use App\Repository\Site\ProductCoatingRepository;
use App\Repository\Site\ProductRecommendationsRepository;
use App\Repository\Site\ProductRepository;

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

        $coatingRepository = new CoatingRepository();
        $data['coatingList'] = $coatingRepository->getCoatingList();

        return $data;
    }

    public function create($data)
    {
        $repository = new ProductRepository();

        $newProductId = $repository->create($data);

        if (empty($newProductId)) {
            $res['errors'][] = 'Ошибка сохранения подукта';
            return $res;
        }

        $productRecommendationsRepository = new ProductRecommendationsRepository();
        if (!empty($params['recommendation_ids'])) {
            foreach ($params['recommendation_ids'] as $recommendation) {
                $productRecommendation = $productRecommendationsRepository->createProductRecommendations($newProductId,
                    $recommendation);

                if (empty($productRecommendation)) {
                    $res['errors'][] = 'Ошибка сохранения рекомендаций';
                    return $res;
                }
            }
        }

        $productCoatingRepository = new ProductCoatingRepository();
        if (!empty($params['coating_ids'])) {
            foreach ($params['coating_ids'] as $coatingId) {
                $productCoating = $productCoatingRepository->createProductCoating($newProductId, $coatingId);

                if (empty($productCoating)) {
                    $res['errors'][] = 'Ошибка сохранения рекомендаций';
                    return $res;
                }
            }
        }

        return $newProductId;
    }

    public function getShowUpdatePageData($id)
    {
        $productRepository = new ProductRepository();
        $data['product'] = $productRepository->getById($id);
        $productList = $productRepository->getAll();

        $categoryRepository = new CategoryRepository();
        $categoryList = $categoryRepository->getAll();

        // удаляем возможность добавления товара в категорию у которой есть дочерняя
        foreach ($categoryList as $key => $category) {
            $childCategory = $categoryRepository->getChildCategoryListById($category['id']);
            if (!empty($childCategory)) {
                unset($categoryList[$key]);
            }
        }

        $productRecommendationsRepository = new ProductRecommendationsRepository();
        $productRecommendations = $productRecommendationsRepository->getProductRecommendationsIdsByProductId($id);

        $productRecommendationIds = [];

        foreach ($productRecommendations as $product) {
            $productRecommendationIds[] = $product['id'];
        }

        foreach ($productList as $key => $product) {
            $productList[$key]['selected'] = in_array($product['id'], $productRecommendationIds);
        }

        $coatingRepository = new CoatingRepository();
        $coatingList = $coatingRepository->getCoatingList();

        $productCoatingRepository = new ProductCoatingRepository();
        $productCoatingList = $productCoatingRepository->getProductCoatingListByProductId($id);

        $coatingIds = [];

        foreach ($productCoatingList as $productCoating) {
            $coatingIds[] = $productCoating['coating_id'];
        }

        foreach ($coatingList as $key => $coating) {
            $coatingList[$key]['selected'] = in_array($coating['id'], $coatingIds);
        }

        $data['productList'] = $productList;
        $data['categoryList'] = $categoryList;
        $data['coatingList'] = $coatingList;
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
                $productRecommendation = $productRecommendationsRepository->createProductRecommendations($params['id'],
                    $recommendation);

                if (empty($productRecommendation)) {
                    $res['errors'][] = 'Ошибка сохранения рекомендаций';
                    return $res;
                }
            }
        }

        $productCoatingRepository = new ProductCoatingRepository();
        $productCoatingList = $productCoatingRepository->getProductCoatingListByProductId($params['id']);


        foreach ($productCoatingList as $productCoating) {
            $productCoatingRepository->deleteProductCoating($productCoating['id']);
        }

        if (!empty($params['coating_ids'])) {
            foreach ($params['coating_ids'] as $coatingId) {
                $productCoating = $productCoatingRepository->createProductCoating($params['id'], $coatingId);

                if (empty($productCoating)) {
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
        return [
            'id' => $params['id'],
            'category_id' => $params['category_id'],
            'name' => $params['name'],
            'description' => $params['description'],
            'content' => $params['content'],
            'file_id' => $params['file_id'],
            'enabled' => $params['enabled'],
            'alias' => TextHelper::getTranslit($params['name']),
            'position' => $params['position'],
            'tag_title' => $params['tag_title'],
            'tag_description' => $params['tag_description'],
            'tag_keywords' => $params['tag_keywords'],
        ];
    }
}

