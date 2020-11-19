<?php

namespace App\Models\Admin;

use App\Repository\BlogRepository;
use App\Repository\PageRepository;

class PageStrategy extends AbstractAdminModel
{
    public $fileDirectory = 'page';

    public function getIndexData($order = null)
    {
        $repository = new BlogRepository();
        $data['pageList'] = $repository->getAll($order);

        return $data;
    }

    public function getShowCreatePageData($order = null)
    {
    }

    public function create($data)
    {
    }

    public function getShowUpdatePageData($id)
    {
        $repository = new PageRepository();
        $data['page'] = $repository->getById($id);

        return $data;
    }

    public function update($data)
    {
        $repository = new PageRepository();
        return $repository->updateById($data);
    }

    public function delete($id)
    {
        $repository = new BlogRepository();
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

    public function getShowDeletePageData($id)
    {
    }

    public function deleteFileConnection($id, $photoId): bool
    {
        return false;
    }

    public function prepareData($params)
    {
        return [
            'id' => $params['id'],
            'name' => $params['name'],
            'content' => $params['content'],
            'file_id' => $params['file_id'],
            'tag_title' => $params['tag_title'],
            'tag_description' => $params['tag_description'],
            'tag_keywords' => $params['tag_keywords'],
        ];
    }
}
