<?php

namespace App\Models\Admin;

use App\Repository\CouponRepository;

class CouponModel implements ModelStrategy
{
    private $couponRepository;

    public function __construct()
    {
        $this->couponRepository = new CouponRepository();
    }

    public function getFileDirectory(): string
    {
        return null;
    }

    public function getIndexData($sort = null)
    {
        $data['couponList'] = $this->couponRepository->getAll($sort);

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
        $data['couponList'] = $this->couponRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        return $this->couponRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['coupon'] = $this->couponRepository->getById($id);

        return $data;
    }

    public function update($file, $data)
    {
        $this->couponRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['coupon'] = $this->couponRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $this->couponRepository->deleteById($id);
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
        return $this->couponRepository->getFileByEntityId($id);
    }

    public function getFiles($id)
    {
        return null;
    }

    public function prepareData($params)
    {
        return [
            'id' => $params['id'],
            'code' => $params['code'],
            'discount' => $params['discount'],
            'quantity' => $params['quantity'],
            'start_date' => $params['start_date'],
            'end_date' =>  $params['end_date'],
        ];
    }
}
