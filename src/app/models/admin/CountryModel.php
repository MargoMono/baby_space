<?php

namespace App\Models\Admin;

use App\Repository\CountryRepository;
use App\Repository\CurrencyRepository;

class CountryModel implements ModelStrategy
{
    public $fileDirectory = 'country';
    private $countryRepository;
    private $currencyRepository;

    public function __construct()
    {
        $this->countryRepository = new CountryRepository();
        $this->currencyRepository = new CurrencyRepository();
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
        $data['countryList'] = $this->countryRepository->getAll();
        $data['currencyList'] = $this->currencyRepository->getAll();

        return $data;
    }

    public function create($data)
    {
        return $this->countryRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['country'] = $this->countryRepository->getById($id);
        $data['currencyList'] = $this->currencyRepository->getAll();

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
            'alpha2' => $params['alpha2'],
            'alpha3' => $params['alpha3'],
            'status' => $params['status'],
            'file_id' => $params['file_id'],
            'currency_id' => $params['currency_id'],
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