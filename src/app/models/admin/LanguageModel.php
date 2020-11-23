<?php

namespace App\Models\Admin;

use App\Repository\LanguageRepository;

class LanguageModel implements ModelStrategy
{
    public $fileDirectory = 'language';
    private $languageRepository;

    public function __construct()
    {
        $this->languageRepository = new LanguageRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $data['languageList'] = $this->languageRepository->getAll($sort);

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
        $data['languageList'] = $this->languageRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        return $this->languageRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['language'] = $this->languageRepository->getById($id);

        return $data;
    }

    public function update($file, $data)
    {
        return $this->languageRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['language'] = $this->languageRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        return $this->languageRepository->deleteById($id);
    }

    public function getFile($id)
    {
        return $this->languageRepository->getFileByEntityId($id);
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
            'code' => $params['code'],
            'alias' => $params['alias'],
            'file_id' => $params['file_id'],
        ];
    }

    public function validation($file, $params)
    {
        // TODO: Implement validation() method.
    }

    public function createFilesConnection($id, $fileId)
    {
        // TODO: Implement createFilesConnection() method.
    }

    public function deleteFileConnection($id, $imageId)
    {
        // TODO: Implement deleteFileConnection() method.
    }
}