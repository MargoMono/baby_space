<?php

namespace App\Model\Admin;

use App\Components\Model;
use App\Helper\FileHelper;
use App\Repository\CategoryRepository;
use App\Components\AdminBase;
use App\Repository\FileRepository;
use RuntimeException;

class CategoryModel extends Model
{
    private $fileDirectory = 'category';

    public function getIndexData($order)
    {
        $categoryRepository = new CategoryRepository();
        $data['categoryList'] = $categoryRepository->getCategoryList($order);

        return $data;
    }

    public function getShowCreatePageData()
    {
        $categoryRepository = new CategoryRepository();
        $categoryList = $categoryRepository->getCategoryList();

        // удаляем возможность добавления подкатегории более 3 уровня
        foreach ($categoryList as $key => $category) {
            $level = $this->getCategoryLevel($category['id']);
            if ($level >= 3) {
                unset($categoryList[$key]);
            }
        }

        $data['categoryList'] = $categoryList;
        return $data;
    }

    public function getCategoryLevel($id)
    {
        $categoryRepository = new CategoryRepository();
        $parent = $categoryRepository->getParentCategoryListById($id);

        $level = 1;
        while ($parent) {
            $level++;
            $parent = $categoryRepository->getParentCategoryListById($parent['id']);
        }

        return $level;
    }

    public function createCategory($file, $params)
    {
        $res['result'] = false;

        $fileHelper = new FileHelper();

        try {
            $image = $fileHelper->uploadFile($file['file'], $this->fileDirectory);
            $imageList = $fileHelper->uploadFiles($file['files'], $this->fileDirectory);
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

        $categoryRepository = new CategoryRepository();
        $newCategoryId = $categoryRepository->createCategory($this->prepareCategoryData($params));

        if (empty($newCategoryId)) {
            $res['errors'][] = 'Ошибка сохранения категории';
            return $res;
        }

        if (!empty($imageList)) {
            foreach ($imageList as $image) {

                $fileId = $fileRepository->createFile($image);

                if (empty($fileId)) {
                    $res['errors'][] = 'Не удалось создать файл';
                    return $res;
                }

                $filesCategoryConnection = $categoryRepository->createFilesCategoryConnection($newCategoryId, $fileId);

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
        $categoryRepository = new CategoryRepository();
        $data['category'] = $categoryRepository->getCategoryById($id);
        $data['categoryList'] = $categoryRepository->getCategoryList();
        $data['categoryFilesList'] = $categoryRepository->getCategoryFilesByCategoryId($id);

        return $data;
    }

    public function updateCategory($file, $params)
    {
        $res['result'] = false;

        $fileHelper = new FileHelper();

        try {
            $image = $fileHelper->uploadFile($file['file'], $this->fileDirectory);
            $imageList = $fileHelper->uploadFiles($file['files'], $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e->getMessage();
            return $res;
        }

        $fileRepository = new FileRepository();

        if (!empty($image)) {
            $fileHelper->deleteFile($params['file_alias'], $this->fileDirectory);

            $params['file_id'] = $fileRepository->createFile($image);
        }

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $categoryRepository = new CategoryRepository();
        $newCategory = $categoryRepository->updateCategory($this->prepareCategoryData($params));

        if (empty($newCategory)) {
            $res['errors'][] = 'Ошибка сохранения категории';
            return $res;
        }

        if (!empty($imageList)) {
            foreach ($imageList as $image) {

                $fileId = $fileRepository->createFile($image);

                if (empty($fileId)) {
                    $res['errors'][] = 'Не удалось создать файл';
                    return $res;
                }

                $filesCategoryConnection = $categoryRepository->createFilesCategoryConnection($params['id'], $fileId);

                if (empty($filesCategoryConnection)) {
                    $res['errors'][] = 'Не удалось создать связь между фото и категорей';
                    return $res;
                }
            }
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowDeletePageData($id)
    {
        $categoryRepository = new CategoryRepository();
        $data['category'] = $categoryRepository->getCategoryById($id);

        return $data;
    }

    public function deleteCategory($data)
    {
        $res['result'] = false;

        $categoryRepository = new CategoryRepository();

        $childCategoryList = $categoryRepository->getChildCategoryListById($data['id']);

        $childCategoryNames = [];

        foreach ($childCategoryList as $childCategory) {
            $childCategoryNames[] = $childCategory['name'];
        }

        $childCategoryNames = implode(',', $childCategoryNames);

        if (!empty($childCategoryNames)) {
            $res['errors'][] = "невозможно удалить категорию при наличии подкатегорий ($childCategoryNames)";
            return $res;
        }

        if ($categoryRepository->deleteCategoryById($data['id'])) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении категории";

        return $res;
    }

    private function prepareCategoryData($params)
    {
        $data = [
            'id' => $params['id'],
            'parent' => $params['parent'],
            'name' => $params['name'],
            'description' => $params['description'],
            'file_id' => $params['file_id'],
            'enabled' => $params['enabled'],
            'alias' => AdminBase::getTranslit($params['name']),
            'position' => $params['position'],
            'tag_title' => $params['tag_title'],
            'tag_description' => $params['tag_description'],
            'tag_keywords' => $params['tag_keywords'],
        ];

        return $data;
    }

    public function photoDelete($id, $photoId)
    {
        $res['result'] = false;

        $fileRepository = new FileRepository();
        $file = $fileRepository->getFileById($photoId);

        $fileHelper = new FileHelper();
        $fileHelper->deleteFile($file['alias'], $this->fileDirectory);

        $categoryRepository = new CategoryRepository();
        if ($categoryRepository->deleteFileCategoryConnection($id, $photoId)) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении статьи";

        return $res;
    }
}

