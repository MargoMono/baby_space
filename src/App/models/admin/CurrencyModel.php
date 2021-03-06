<?php

namespace App\Models\Admin;

use App\Repository\CurrencyRepository;

class CurrencyModel implements ModelStrategy
{
    private $currencyRepository;

    public function __construct()
    {
        $this->currencyRepository = new CurrencyRepository();
    }

    public function getFileDirectory(): string
    {
        return null;
    }

    public function getIndexData($sort = null)
    {
        $data['currencyList'] = $this->currencyRepository->getAll($sort);

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
        $data['currencyList'] = $this->currencyRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        return $this->currencyRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['currency'] = $this->currencyRepository->getById($id);

        return $data;
    }

    public function update($file, $data)
    {
        $this->currencyRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['currency'] = $this->currencyRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $this->currencyRepository->deleteById($id);
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
        return null;
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
        ];
    }
}