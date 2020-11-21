<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\NewRepository;

class NewStrategy
{
    public $fileDirectory = 'new';

    public function getIndexData($sort = null)
    {
        $repository = new NewRepository();
        $data['blogList'] = $repository->getAll($sort);

        return $data;
    }

    public function getShowCreatePageData($sort = null)
    {
        $repository = new NewRepository();
        $data['blogList'] = $repository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        $repository = new NewRepository();

        return $repository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $repository = new NewRepository();
        $data['new'] = $repository->getById($id);

        return $data;
    }

    public function update($data)
    {
        $repository = new NewRepository();
        return $repository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $repository = new NewRepository();
        $data['new'] = $repository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $repository = new NewRepository();
        return $repository->deleteById($id);
    }

    public function addFilesConnection($files, $id)
    {
    }

    public function createFilesConnection($id, $fileId)
    {
    }

    public function updateFilesConnection($files, $id)
    {
    }

    public function deleteFileConnection($id, $photoId): bool
    {
        return  false;
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

}