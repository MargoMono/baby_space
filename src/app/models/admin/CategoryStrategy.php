<?php

namespace App\Model\Admin;

use App\Helper\BreadcrumbsHelper;
use App\Helper\TextHelper;
use App\Modules\FileUploader;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\FileRepository;

class CategoryStrategy extends AbstractAdminModel
{
    public $fileDirectory = 'category';

    public function getIndexData($order = null)
    {
        $repository = new CategoryRepository();
        $data['categoryList'] = $repository->getAll($order);

        foreach ($data['categoryList'] as $key => $category) {
            $data['categoryList'][$key]['breadcrumbs'] = BreadcrumbsHelper::getBreadcrumbsInString($category['id']);
        }

        return $data;
    }

    public function getShowCreatePageData($order = null)
    {
        $repository = new CategoryRepository();
        $data['categoryList'] = $repository->getAll($order);

        foreach ($data['categoryList'] as $key => $category) {
            $data['categoryList'][$key]['breadcrumbs'] = BreadcrumbsHelper::getBreadcrumbsInString($category['id']);

            // удаляем возможность добавления подкатегории более 3 уровня
            $level = $this->getCategoryLevel($category['id']);
            if ($level >= 3) {
                unset($data[$key]);
            }
        }
    }

    public function create($data)
    {
        $repository = new CategoryRepository();

        return $repository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $repository = new CategoryRepository();

        $data['category'] = $repository->getById($id);
        $data['categoryList'] = $repository->getAll();
        $data['categoryFilesList'] = $repository->getCategoryFilesByCategoryId($id);

        foreach ($data['categoryList'] as $key => $category) {
            $data['categoryList'][$key]['breadcrumbs'] = BreadcrumbsHelper::getBreadcrumbsInString($category['id']);

            if ($data['categoryList'][$key]['id'] == $id) {
                unset($data['categoryList'][$key]);
            }

            // удаляем возможность добавления подкатегории более 3 уровня
            $level = $this->getCategoryLevel($category['id']);
            if ($level >= 3) {
                unset($data['categoryList'][$key]);
            }
        }

        return $data;
    }

    public function update($data)
    {
        $repository = new CategoryRepository();
        return $repository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $categoryRepository = new CategoryRepository();
        $data['category'] = $categoryRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $repository = new CategoryRepository();
        return $repository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
        $repository = new CategoryRepository();

        return $repository->createFilesConnection($id, $fileId);
    }

    public function updateFilesConnection($files, $categoryId)
    {
        $fileUploader = new FileUploader();

        try {
            $imageList = $fileUploader->uploadSeveral($files, $this->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();

        if (empty($imageList)) {
            $res['result'] = true;
            return $res;
        }

        foreach ($imageList as $image) {

            $fileId = $fileRepository->createFile($image);

            if (empty($fileId)) {
                $res['errors'][] = 'Не удалось создать файл';
                return $res;
            }

            $repository = new CategoryRepository();
            $filesCategoryConnection = $repository->createFilesConnection($categoryId, $fileId);

            if (empty($filesCategoryConnection)) {
                $res['errors'][] = 'Не удалось создать связь между фото и категорей';
                return $res;
            }
        }

        return null;
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

    public function deleteFileConnection($id, $photoId): bool
    {
        $categoryRepository = new CategoryRepository();

        if ($categoryRepository->deleteFileCategoryConnection($id, $photoId)) {
            return true;
        }

        return false;
    }

    public function prepareData($params)
    {
        return [
            'id' => $params['id'],
            'parent' => $params['parent'] ?? 0,
            'name' => $params['name'],
            'description' => $params['description'],
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