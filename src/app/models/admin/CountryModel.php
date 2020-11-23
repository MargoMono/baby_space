<?php

namespace App\Models\Admin;

use App\Repository\CountryRepository;

class CountryModel implements ModelStrategy
{
    public $fileDirectory = 'country';
    private $countryRepository;

    public function __construct()
    {
        $this->countryRepository = new CountryRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $data['countryList'] = $this->countryRepository->getAll($sort);

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
        $data['countryList'] = $this->countryRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        return $this->countryRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['country'] = $this->countryRepository->getById($id);

        return $data;
    }

    public function update($file, $data)
    {
        return $this->countryRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['country'] = $this->countryRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        return $this->countryRepository->deleteById($id);
    }

    public function getFile($id)
    {
        return $this->countryRepository->getFileByEntityId($id);
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
            'iso_code_2' => $params['iso_code_2'],
            'iso_code_3' => $params['iso_code_3'],
            'status' => $params['status'],
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