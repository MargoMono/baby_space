<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\CategoryRepository;

class CategoryModel implements ModelStrategy
{
    public $fileDirectory = 'category';

    private $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $data['categoryList'] = $this->categoryRepository->getAll($sort);

        if($sort['desc'] == 'DESC'){
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;

        return $data;
    }

    public function getShowCreatePageData($sort = null)
    {
        $data['categoryList'] = $this->categoryRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        return $this->categoryRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['category'] = $this->categoryRepository->getById($id);

        return $data;
    }

    public function update($data)
    {
        return $this->categoryRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['category'] = $this->categoryRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        return $this->categoryRepository->deleteById($id);
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

    public function prepareData($params)
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
