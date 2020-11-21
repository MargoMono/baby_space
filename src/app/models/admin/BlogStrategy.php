<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\BlogRepository;

class BlogStrategy implements ModelStrategy
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

    public function update($data)
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

    public function prepareData($params)
    {
        return [
            'id' => $params['id'],
            'name' => $params['name'],
            'description' => $params['description'],
            'content' => $params['content'],
            'file_id' => $params['file_id'],
            'alias' => TextHelper::getTranslit($params['name']),
            'tag_title' => $params['tag_title'],
            'tag_description' => $params['tag_description'],
            'tag_keywords' => $params['tag_keywords'],
        ];
    }


    public function validation($file, $params)
    {
        // TODO: Implement validation() method.
    }

    public function addFileConnection($file)
    {
        // TODO: Implement addFileConnection() method.
    }

    public function updateFileConnection($file, $params)
    {
        // TODO: Implement updateFileConnection() method.
    }

    public function getFile($id)
    {
        // TODO: Implement getFile() method.
    }

    public function getFiles($id)
    {
        // TODO: Implement getFiles() method.
    }
}