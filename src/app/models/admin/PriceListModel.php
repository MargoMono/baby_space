<?php

namespace App\Model\Admin;

use App\Components\Model;
use App\Modules\FileUploader;
use App\Repository\PriceListOrderRepository;
use App\Repository\CategoryRepository;
use App\Repository\FileRepository;
use App\Repository\BlogRepository;
use App\Repository\PriceListRepository;
use RuntimeException;

class PriceListModel extends Model
{
    private $fileDirectory = 'price-list';

    public function getIndexData($order)
    {
        $newRepository = new PriceListRepository();
        $data['priceList'] = $newRepository->getPriceList($order);

        return $data;
    }

    public function create($file, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        if ($file["type"] != 'application/pdf') {
            $res['errors'][] = 'Недопустимое расширение для файла, только pdf';
            return $res;
        }

        try {
            $alias = $fileUploader->uploadOne($file,  $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e;
            return $res;
        }

        $fileRepository = new FileRepository();
        $params['file_id'] = $fileRepository->createFile($alias);

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $priceListRepository = new PriceListRepository();
        $new = $priceListRepository->createPrice($this->prepareData($params));

        if (empty($new)) {
            $res['errors'][] = 'Ошибка сохранения новости';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowUpdatePageData($id)
    {
        $categoryRepository = new PriceListRepository();
        $data['price'] = $categoryRepository->getPriceById($id);

        return $data;
    }

    public function update($file, $params)
    {
        $res['result'] = false;

        $fileUploader = new FileUploader();

        if ($file["type"] != 'application/pdf') {
            $res['errors'][] = 'Недопустимое расширение для файла, только pdf';
            return $res;
        }

        try {
            $alias = $fileUploader->uploadOne($file, $this->fileDirectory);
        } catch (RuntimeException $e) {
            $res['errors'][] = $e->getMessage();
            return $res;
        }

        if (!empty($alias)) {
            $fileUploader->deleteFile($params['file_alias'], $this->fileDirectory);
            $fileRepository = new FileRepository();
            $params['file_id'] = $fileRepository->createFile($alias);
        }

        if (empty($params['file_id'])) {
            $res['errors'][] = 'Ошибка сохранения файла';
            return $res;
        }

        $priceListRepository = new PriceListRepository();
        $newPrice = $priceListRepository->updatePrice($this->prepareData($params));

        if (empty($newPrice)) {
            $res['errors'][] = 'Ошибка сохранения статьи';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowDeletePageData($id)
    {
        $priceListRepository = new PriceListRepository();
        $data['price'] = $priceListRepository->getPriceById($id);

        return $data;
    }

    public function delete($data)
    {
        $res['result'] = false;

        $priceListRepository = new PriceListRepository();

        if ($priceListRepository->deletePriceById($data['id'])) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении статьи";

        return $res;
    }

    public function getOrderData($order)
    {
        $catalogOrderRepository = new PriceListOrderRepository();
        $data['clientList'] = $catalogOrderRepository->getClientList($order);

        return $data;
    }

    private function prepareData($params)
    {
        $data = [
            'id' => $params['id'],
            'name' => $params['name'],
            'file_id' => $params['file_id']
        ];

        return $data;
    }
}

