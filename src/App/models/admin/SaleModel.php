<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\LanguageRepository;
use App\Repository\SaleDescriptionRepository;
use App\Repository\SaleRepository;

class SaleModel implements ModelStrategy
{
    private $saleRepository;
    private $languageRepository;
    private $saleDescriptionRepository;

    public function __construct()
    {
        $this->saleRepository = new SaleRepository();
        $this->saleDescriptionRepository = new SaleDescriptionRepository();
        $this->languageRepository = new LanguageRepository();
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
        $sale = $this->saleRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['sale'] = $this->saleDescriptionRepository->getByIdAndLanguageId($sale['id'],
                $language['id']);
        }

        $data['sale'] = $sale;
        $data['languages'] = $languages;

        return $data;
    }

    public function update($file, $data)
    {
        $this->saleRepository->updateById($data);

        foreach ($data['description'] as $description) {
            $productDescriptionExist = $this->saleDescriptionRepository->getByIdAndLanguageId($data['id'],
                $description['language_id']);

            if (empty($productDescriptionExist)) {
                $this->saleDescriptionRepository->create($data['id'], $description);
            } else {
                $this->saleDescriptionRepository->updateById($description);
            }
        }
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
        $languages = $this->languageRepository->getAll();

        $paramsDescription = [];

        foreach ($languages as $language) {
            $paramsDescription[$language['id']] = [
                'language_id' => $language['id'],
                'id' => $params['id-' . $language['id']],
                'sale' => $params['sale-' . $language['id']],
                'name' => $params['name-' . $language['id']],
                'description' => $params['description-' . $language['id']],
            ];
        }

        return [
            'id' => $params['id'],
            'status' => $params['status'],
            'description' => $paramsDescription,
        ];
    }
}