<?php

namespace App\Models\Admin;

use App\Repository\SaleRepository;

class SaleModel implements ModelStrategy
{
    private $saleRepository;

    public function __construct()
    {
        $this->saleRepository = new SaleRepository();
    }

    public function getFileDirectory()
    {
        return null;
    }

    public function getIndexData($sort = null)
    {
    }

    public function getShowCreatePageData($sort = null)
    {
    }

    public function create($data)
    {
    }

    public function getShowUpdatePageData($id)
    {
        $data['sale'] = $this->saleRepository->getById(1);

        return $data;
    }

    public function update($file, $data)
    {
        $this->saleRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
    }

    public function delete($id)
    {
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
    }

    public function getFiles($id)
    {
        return null;
    }

    public function prepareData($params)
    {
        return [
            'id' => $params['id'],
            'sale' => $params['sale'],
            'name' => $params['name'],
            'description' => $params['description'],
            'status' => $params['status'],
        ];
    }
}