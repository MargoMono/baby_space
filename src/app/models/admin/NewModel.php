<?php

namespace App\Models\Admin;

use App\Repository\NewRepository;

class NewModel implements ModelStrategy
{
    public $fileDirectory = 'new';

    private $newRepository;


    public function __construct()
    {
        $this->newRepository = new NewRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $data['newList'] = $this->newRepository->getAll($sort);

        if ($sort['desc'] == 'DESC') {
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;


        return $data;
    }

    public function getShowCreatePageData($sort = null)
    {
        $data['newList'] = $this->newRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        return $this->newRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['new'] = $this->newRepository->getById($id);

        return $data;
    }

    public function update($file, $data)
    {
        $this->newRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['new'] = $this->newRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $this->newRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
        return null;
    }

    public function deleteFileConnection($id, $imageId)
    {
        return null;
    }

    public function getFile($id)
    {
        return $this->newRepository->getFileByEntityId($id);
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
            'description' => $params['description'],
            'file_id' => $params['file_id'],
        ];
    }
}