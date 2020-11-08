<?php

namespace App\Model\Admin;

use App\Helper\TextHelper;
use App\Modules\FileUploader;
use App\Repository\CategoryRepository;
use App\Repository\FileRepository;

class CategoryStrategy implements Strategy
{
    public $fileDirectory = 'category';

    public function getRepository()
    {
        return new CategoryRepository();
    }

    public function modifyCreatePageData($data)
    {
        // удаляем возможность добавления подкатегории более 3 уровня
        foreach ($data as $key => $category) {
            $level = $this->getCategoryLevel($category['id']);
            if ($level >= 3) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    public function addFileConnection($file)
    {
        $fileUploader = new FileUploader();

        try {
            $alias = $fileUploader->uploadOne($file, $this->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();

        return $fileRepository->createFile($alias);
    }

    public function addFilesConnection($files, $categoryId)
    {
        $fileUploader = new FileUploader();

        try {
            $imageList = $fileUploader->uploadSeveral($files, $this->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();

        foreach ($imageList as $image) {

            $fileId = $fileRepository->createFile($image);

            if (empty($fileId)) {
                $res['errors'][] = 'Не удалось создать файл';
                return $res;
            }
            $repository = $this->getRepository();
            $filesCategoryConnection = $repository->createFilesCategoryConnection($categoryId, $fileId);

            if (empty($filesCategoryConnection)) {
                $res['errors'][] = 'Не удалось создать связь между фото и категорей';
                return $res;
            }
        }

        return null;
    }

    public function updateFileConnection($file, $params)
    {
        $fileUploader = new FileUploader();

        try {
            $image = $fileUploader->uploadOne($file, $this->fileDirectory);
        } catch (\RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        if (!empty($image)) {
            $fileUploader->deleteFile($params['file_alias'], $this->fileDirectory);
        }

        $fileRepository = new FileRepository();

        return $fileRepository->createFile($image);
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

        foreach ($imageList as $image) {

            $fileId = $fileRepository->createFile($image);

            if (empty($fileId)) {
                $res['errors'][] = 'Не удалось создать файл';
                return $res;
            }
            $repository = $this->getRepository();
            $filesCategoryConnection = $repository->createFilesCategoryConnection($categoryId, $fileId);

            if (empty($filesCategoryConnection)) {
                $res['errors'][] = 'Не удалось создать связь между фото и категорей';
                return $res;
            }
        }

        return null;
    }

    public function getShowUpdatePageData($id)
    {
        $repository = $this->getRepository();

        $data['category'] = $repository->getById($id);
        $data['categoryList'] = $repository->getAll();
        $data['categoryFilesList'] = $repository->getCategoryFilesByCategoryId($id);

        return $data;
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

    public function getShowDeletePageData($id)
    {
        $categoryRepository = new CategoryRepository();
        $data['category'] = $categoryRepository->getById($id);

        return $data;
    }

    public function photoDelete($id, $photoId)
    {
        $res['result'] = false;

        $fileRepository = new FileRepository();
        $file = $fileRepository->getFileById($photoId);

        $fileUploader = new FileUploader();
        $fileUploader->deleteFile($file['alias'], $this->fileDirectory);

        $categoryRepository = new CategoryRepository();
        if ($categoryRepository->deleteFileCategoryConnection($id, $photoId)) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении статьи";

        return $res;
    }
}