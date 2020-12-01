<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class CategoryModel implements ModelStrategy
{
    public $fileDirectory = 'category';

    private $categoryRepository;
    private $productRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
        $this->productRepository = new ProductRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null): array
    {
        $data['categoryList'] = $this->categoryRepository->getAll($sort);

        if ($sort['desc'] == 'DESC') {
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;

        return $data;
    }

    public function getShowCreatePageData($sort = null): array
    {
        $data['categoryList'] = $this->categoryRepository->getAll($sort);

        return $data;
    }

    public function create($data): int
    {
        return $this->categoryRepository->create($data);
    }

    public function getShowUpdatePageData($id): array
    {
        $data['category'] = $this->categoryRepository->getById($id);

        return $data;
    }

    public function update($file, $data): void
    {
        $this->categoryRepository->updateById($data);
    }

    public function getShowDeletePageData($id): array
    {
        $data['productExist'] = empty($this->productRepository->getAllByCategoryId($id)) ? false : true;
        $data['category'] = $this->categoryRepository->getById($id);

        return $data;
    }

    public function delete($id): void
    {
        $this->categoryRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
    }

    public function deleteFileConnection($id, $imageId)
    {
    }

    public function getFile($id)
    {
        return $this->categoryRepository->getFileByEntityId($id);
    }

    public function getFiles($id)
    {
        return null;
    }

    public function prepareData($params): array
    {
        return [
            'id' => $params['id'],
            'parent_id' => $params['parent_id'],
            'name' => $params['name'],
            'description' => $params['description'],
            'file_id' => $params['file_id'],
            'status' => $params['status'],
            'alias' => TextHelper::getTranslit($params['name']),
            'tag' => $params['tag'],
            'meta_title' => $params['meta_title'],
            'meta_description' => $params['meta_description'],
            'meta_keyword' => $params['meta_keyword'],
        ];
    }
}
