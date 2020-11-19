<?php

namespace App\Models\Admin;

use App\Helper\TextHelper;
use App\Repository\BlogRepository;
use App\Repository\LanguageRepository;

class LanguageStrategy extends AbstractAdminModels
{
    public $fileDirectory = 'language';

    public function getIndexData($order = null)
    {
        $repository = new LanguageRepository();
        $data['languageList'] = $repository->getAll($order);

        return $data;
    }

    public function getShowCreatePageData($order = null)
    {
        $repository = new LanguageRepository();
        $data['languageList'] = $repository->getAll($order);

        return $data;
    }

    public function create($data)
    {
        $repository = new LanguageRepository();
        return $repository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $repository = new LanguageRepository();
        $data['language'] = $repository->getById($id);

        return $data;
    }

    public function update($data)
    {
        $repository = new LanguageRepository();
        return $repository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $repository = new LanguageRepository();
        $data['language'] = $repository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $repository = new LanguageRepository();
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
       return false;
    }

    public function prepareData($params)
    {
        return [
            'id' => $params['id'],
            'name' => $params['name'],
            'code' => $params['code'],
            'alias' => $params['alias'],
            'file_id' => $params['file_id'],
        ];
    }
}