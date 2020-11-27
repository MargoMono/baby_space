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

    public function getFileDirectory(): string
    {
        return null;
    }

    public function getIndexData($sort = null)
    {
        $data['saleList'] = $this->saleRepository->getAll($sort);

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
        $data['saleList'] = $this->saleRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        return $this->saleRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['sale'] = $this->saleRepository->getById($id);

        return $data;
    }

    public function update($file, $data)
    {
        return $this->saleRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['sale'] = $this->saleRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        return $this->saleRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
    }

    public function deleteFileConnection($id, $imageId)
    {
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
            'size' => $params['size'],
            'product_id' => $params['product_id'],
            'country_id' => $params['country_id'],
        ];
    }
}
