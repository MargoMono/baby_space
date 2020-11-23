<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\BlogRepository;

class BlogModel implements ModelStrategy
{
    public $fileDirectory = 'blog';

    private $blogRepository;

    public function __construct()
    {
        $this->blogRepository = new BlogRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $data['blogList'] = $this->blogRepository->getAll($sort);

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
        $data['blogList'] = $this->blogRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        return $this->blogRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['blog'] = $this->blogRepository->getById($id);

        return $data;
    }

    public function update($file, $data)
    {
        return $this->blogRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['blog'] = $this->blogRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        return $this->blogRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
    }

    public function deleteFileConnection($id, $imageId)
    {
    }

    public function getFile($id)
    {
        return $this->blogRepository->getFileByEntityId($id);
    }

    public function getFiles($id)
    {
        return null;
    }

    public function prepareData($params)
    {
        return [
            'id' => $params['id'],
            'name' => $params['name'],
            'short_description' => $params['short_description'],
            'description' => $params['description'],
            'file_id' => $params['file_id'],
            'alias' => TextHelper::getTranslit($params['name']),
            'tag' =>  $params['tag'],
            'meta_title' => $params['meta_title'],
            'meta_description' => $params['meta_description'],
            'meta_keyword' => $params['meta_keyword'],
        ];
    }
}
