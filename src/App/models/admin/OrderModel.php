<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\CategoryRepository;
use App\Repository\CountryRepository;
use App\Repository\LanguageRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductDescriptionRepository;
use App\Repository\ProductRecommendationsRepository;
use App\View\View;

class OrderModel implements ModelStrategy
{
    public $fileDirectory = 'order';
    public $orderRepository;
    public $categoryRepository;
    public $productDescriptionRepository;
    public $productRecommendationsRepository;
    public $languageRepository;
    public $countryRepository;
    public $view;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->productDescriptionRepository = new ProductDescriptionRepository();
        $this->productRecommendationsRepository = new ProductRecommendationsRepository();
        $this->languageRepository = new LanguageRepository();
        $this->countryRepository = new CountryRepository();

        $this->view = new View();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $data['orderList'] = $this->orderRepository->getAll($sort);
        $data['orderStatusList'] = $this->orderRepository->getAllStatus($sort);
        $data['orderPaymentMethodList'] = $this->orderRepository->getAllPaymentMethods($sort);
        $data['orderShippingMethodList'] = $this->orderRepository->getAllShippingMethods($sort);

        if($sort['desc'] == 'DESC'){
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;

        return $data;
    }

    public function getFilteredData($data)
    {
        return $this->orderRepository->getFilteredData($data);
    }

    public function getShowCreatePageData($sort = null)
    {
        return null;
    }

    public function create($data)
    {
       return null;
    }

    public function actionShowViewPage($id)
    {
        $data['order'] = $this->orderRepository->getById($id);
        $data['orderProductList'] = $this->orderRepository->getOrderProductsByOrderId($id);

        $this->view->generate('admin/order/view.twig', $data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['order'] = $this->orderRepository->getById($id);
        $data['countryList'] =  $this->countryRepository->getAll();
        $data['orderStatusList'] = $this->orderRepository->getAllStatus();
        $data['orderPaymentMethodList'] = $this->orderRepository->getAllPaymentMethods();
        $data['orderShippingMethodList'] = $this->orderRepository->getAllShippingMethods();

        return $data;
    }

    public function update($file, $data)
    {
        $this->orderRepository->updateById($data);
    }

    public function getShowDeletePageData($id)
    {
        $data['order'] = $this->orderRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $this->orderRepository->deleteById($id);
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
            'client' => $params['client'],
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'email' => $params['email'],
            'telephone' => $params['telephone'],
            'country' => $params['country'],
            'city' => $params['city'],
            'address' => $params['address'],
            'postcode' => $params['postcode'],
            'payment_method_id' => $params['payment_method_id'],
            'shipping_method_id' => $params['shipping_method_id'],
            'total_price' => $params['total_price'],
            'currency' => $params['currency'],
            'comment' => $params['comment'],
            'status_id' => $params['status_id'],
            'created_at' => $params['created_at'],
        ];
    }

    public function validation($file, $params)
    {
        // TODO: Implement validation() method.
    }


}

