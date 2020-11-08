<?php

namespace App\Model\Admin;

use App\Model\Model;
use App\Helper\CatalogHelper;
use App\Modules\FileUploader;
use App\Helper\TextHelper;
use App\Repository\CategoryRepository;
use App\Repository\CoatingRepository;
use App\Repository\DesignRepository;
use App\Repository\FileRepository;
use App\Repository\Site\ProductCoatingRepository;
use App\Repository\Site\ProductDesignRepository;
use App\Repository\Site\ProductPageKindRepository;
use App\Repository\Site\ProductRecommendationsRepository;
use App\Repository\Site\ProductRepository;
use RuntimeException;

class ProductModel extends Model
{
    private $fileDirectory = 'product';

    public function getIndexData($order)
    {
        $categoryRepository = new ProductRepository();
        $data['productList'] = $categoryRepository->getProductList($order);

        return $data;
    }

    public function getShowCreatePageData()
    {
        $categoryRepository = new ProductRepository();
        $data['productList'] = $categoryRepository->getProductList();

        $categoryRepository = new CategoryRepository();
        $categoryList = $categoryRepository->getCategoryList();

        // удаляем возможность добавления товара в категорию у которой есть дочерняя
        foreach ($categoryList as $key => $category) {
            $childCategory = $categoryRepository->getChildCategoryListById($category['id']);
            if (!empty($childCategory)) {
                unset($categoryList[$key]);
            }
        }

        $data['categoryList'] = $categoryList;

        $coatingRepository = new CoatingRepository();
        $data['coatingList'] = $coatingRepository->getCoatingList();

        $designRepository = new DesignRepository();
        $data['designList'] = $designRepository->getDesignList();

        return $data;
    }

    public function create($file, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        try {
            $image = $fileUploader->uploadOne($file['file'], $this->fileDirectory);
            $imageList = $fileUploader->uploadSeveral($file['files'], $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();
        $params['file_id'] = $fileRepository->createFile($image);

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $productRepository = new ProductRepository();
        $newProductId = $productRepository->createProduct($this->prepareProductData($params));

        if (empty($newProductId)) {
            $res['errors'][] = 'Ошибка сохранения подукта';
            return $res;
        }

        $productRecommendationsRepository = new ProductRecommendationsRepository();
        if (!empty($params['recommendation_ids'])) {
            foreach ($params['recommendation_ids'] as $recommendation) {
                $productRecommendation = $productRecommendationsRepository->createProductRecommendations($newProductId, $recommendation);

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

        $productDesignRepository = new ProductDesignRepository();
        if (!empty($params['design_ids'])) {
            foreach ($params['design_ids'] as $designId) {
                $productDesign = $productDesignRepository->createProductDesign($newProductId, $designId);

                if (empty($productDesign)) {
                    $res['errors'][] = 'Ошибка сохранения рекомендаций';
                    return $res;
                }
            }
        }

        if (!empty($imageList)) {
            foreach ($imageList as $image) {

                $fileId = $fileRepository->createFile($image);

                if (empty($fileId)) {
                    $res['errors'][] = 'Не удалось создать файл';
                    return $res;
                }

                $filesCategoryConnection = $productRepository->createFilesProductConnection($newProductId, $fileId);

                if (empty($filesCategoryConnection)) {
                    $res['errors'][] = 'Не удалось создать связь между фото и категорей';
                    return $res;
                }
            }
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowUpdatePageData($id)
    {
        $productRepository = new ProductRepository();
        $data['product'] = $productRepository->getProductById($id);
        $productList = $productRepository->getProductList();

        $categoryRepository = new CategoryRepository();
        $categoryList = $categoryRepository->getCategoryList();

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


        $designRepository = new DesignRepository();
        $designList = $designRepository->getDesignList();

        $productDesignRepository = new ProductDesignRepository();
        $productDesignList = $productDesignRepository->getProductDesignListByProductId($id);

        $designIds = [];

        foreach ($productDesignList as $productDesign) {
            $designIds[] = $productDesign['design_id'];
        }

        foreach ($designList as $key => $design) {
            $designList[$key]['selected'] = in_array($design['id'], $designIds);
        }

        $data['productList'] = $productList;
        $data['categoryList'] = $categoryList;
        $data['coatingList'] = $coatingList;
        $data['designList'] = $designList;
        $data['productFilesList'] = $productRepository->getProductFilesByProductId($id);

        return $data;
    }

    public function update($file, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        try {
            $image = $fileUploader->uploadOne($file['file'], $this->fileDirectory);
            $imageList = $fileUploader->uploadSeveral($file['files'], $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e->getMessage();
            return $res;
        }

        $fileRepository = new FileRepository();

        if (!empty($image)) {
            $fileUploader->deleteFile($params['file_alias'], $this->fileDirectory);
            $params['file_id'] = $fileRepository->createFile($image);
        }

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $productRepository = new ProductRepository();
        $newCategory = $productRepository->updateProductById($this->prepareProductData($params));

        if (empty($newCategory)) {
            $res['errors'][] = 'Ошибка сохранения продукта';
            return $res;
        }

        $productRecommendationsRepository = new ProductRecommendationsRepository();
        $productRecommendations = $productRecommendationsRepository->getProductRecommendationsIdsByProductId($params['id']);

        foreach ($productRecommendations as $product) {
            $productRecommendationsRepository->deleteProductRecommendations($product['id'], $params['id']);
        }

        if (!empty($params['recommendation_ids'])) {
            foreach ($params['recommendation_ids'] as $recommendation) {
                $productRecommendation = $productRecommendationsRepository->createProductRecommendations($params['id'], $recommendation);

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

        $productDesignRepository = new ProductDesignRepository();
        $productDesignList = $productDesignRepository->getProductDesignListByProductId($params['id']);


        foreach ($productDesignList as $productDesign) {
            $productDesignRepository->deleteProductDesign($productDesign['id']);
        }

        if (!empty($params['design_ids'])) {
            foreach ($params['design_ids'] as $designId) {
                $productDesign = $productDesignRepository->createProductDesign($params['id'], $designId);

                if (empty($productDesign)) {
                    $res['errors'][] = 'Ошибка сохранения рекомендаций';
                    return $res;
                }
            }
        }

        if (!empty($imageList)) {
            foreach ($imageList as $image) {

                $fileId = $fileRepository->createFile($image);

                if (empty($fileId)) {
                    $res['errors'][] = 'Не удалось создать файл';
                    return $res;
                }

                $filesProductConnection = $productRepository->createFilesProductConnection($params['id'], $fileId);

                if (empty($filesProductConnection)) {
                    $res['errors'][] = 'Не удалось создать связь между фото и продуктом';
                    return $res;
                }
            }
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowDeletePageData($id)
    {
        $productRepository = new ProductRepository();
        $data['product'] = $productRepository->getProductById($id);

        return $data;
    }

    public function delete($data)
    {
        $res['result'] = false;

        $productRepository = new ProductRepository();

        if (!$productRepository->deleteFilesProductConnectionByProductId($data['id'])) {
            $res['errors'][] = 'ошибка при удалении продукта';
            return $res;
        }

        if (!$productRepository->deleteProductById($data['id'])) {
            $res['errors'][] = 'ошибка при удалении продукта';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    private function prepareProductData($params)
    {
        $data = [
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

        return $data;
    }

    public function getShowProductPageKindPage()
    {
        $productPageKindRepository = new ProductPageKindRepository();
        $data['enableProductPageKind'] = $productPageKindRepository->getEnableProductPageKind();

        return $data;
    }

    public function updateKindPage($data)
    {
        $productPageKindRepository = new ProductPageKindRepository();

        $productPageKindRepository->updateProductPageKind($data['kind']);
        $data['enableProductPageKind'] = $productPageKindRepository->getEnableProductPageKind();

        return $data;
    }

    public function photoDelete($id, $photoId)
    {
        $res['result'] = false;

        $fileRepository = new FileRepository();
        $file = $fileRepository->getFileById($photoId);

        $fileUploader = new FileUploader();
        $fileUploader->deleteFile($file['alias'], $this->fileDirectory);

        $productRepository = new ProductRepository();
        if ($productRepository->deleteFileProductConnection($id, $photoId)) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении статьи";

        return $res;
    }
}

