<?php

namespace App\Models\Admin;

use App\Helpers\BreadcrumbsHelper;
use App\Helpers\TextHelper;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;

class CategoryStrategy implements ModelStrategy
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

    public function getFile($id)
    {
        return $this->categoryRepository->getFileByEntityId($id);
    }

    public function getFiles($id)
    {
        return null;
    }

    public function delete($id)
    {
        return $this->categoryRepository->deleteById($id);
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


    public function validation($file, $params)
    {
        // TODO: Implement validation() method.
    }


}