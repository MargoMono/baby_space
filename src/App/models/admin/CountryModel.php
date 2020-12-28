<?php

namespace App\Models\Admin;

use App\Repository\CountryDescriptionRepository;
use App\Repository\CountryRepository;
use App\Repository\CurrencyRepository;
use App\Repository\LanguageRepository;

class CountryModel implements ModelStrategy
{
    public $fileDirectory = 'country';

    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;

    /**
     * @var CountryRepository
     */
    private $countryRepository;

    /**
     * @var CountryDescriptionRepository
     */
    private $countryDescriptionRepository;

    public function __construct()
    {
        $this->languageRepository = new LanguageRepository();
        $this->currencyRepository = new CurrencyRepository();
        $this->countryRepository = new CountryRepository();
        $this->countryDescriptionRepository = new CountryDescriptionRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $data['countryList'] = $this->countryRepository->getAll($sort);

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
        $data['languageList'] = $this->languageRepository->getAll();
        $data['countryList'] = $this->countryRepository->getAll();
        $data['currencyList'] = $this->currencyRepository->getAll();

        return $data;
    }

    public function create($data)
    {
        $newEntityId = $this->countryRepository->create($data);

        foreach ($data['description'] as $description) {
            $this->countryDescriptionRepository->create($newEntityId, $description);
        }

        return $newEntityId;
    }

    public function getShowUpdatePageData($id)
    {
        $country = $this->countryRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['country'] = $this->countryDescriptionRepository->getByIdAndLanguageId($country['id'],
                $language['id']);
        }

        $data['country'] = $country;
        $data['languageList'] = $languages;
        $data['currencyList'] = $this->currencyRepository->getAll();

        return $data;
    }

    public function update($file, $data)
    {
        $this->countryRepository->updateById($data);

        foreach ($data['description'] as $description) {
            $productDescriptionExist = $this->countryDescriptionRepository->getByIdAndLanguageId($data['id'],
                $description['language_id']);

            if (empty($productDescriptionExist)) {
                $this->countryDescriptionRepository->create($data['id'], $description);
            } else {
                $this->countryDescriptionRepository->updateById($description);
            }
        }
    }

    public function getShowDeletePageData($id)
    {
        $data['country'] = $this->countryRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $this->countryRepository->deleteById($id);
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
        return $this->countryRepository->getFileByEntityId($id);
    }

    public function getFiles($id)
    {
        return null;
    }

    public function prepareData($params): array
    {
        $languages = $this->languageRepository->getAll();

        $paramsDescription = [];

        foreach ($languages as $language) {
            $paramsDescription[$language['id']] = [
                'language_id' => $language['id'],
                'id' => $params['id-' . $language['id']],
                'name' => $params['name-' . $language['id']],
            ];
        }

        return [
            'id' => $params['id'],
            'alpha2' => $params['alpha2'],
            'alpha3' => $params['alpha3'],
            'status' => $params['status'],
            'file_id' => $params['file_id'],
            'currency_id' => $params['currency_id'],
            'description' => $paramsDescription,
        ];
    }
}