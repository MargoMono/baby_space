<?php

namespace App\Models\Admin;

use App\Repository\BlogDescriptionRepository;
use App\Repository\LanguageRepository;
use App\Repository\NewDescriptionRepository;
use App\Repository\NewRepository;

class NewModel implements ModelStrategy
{
    public $fileDirectory = 'new';

    private $newRepository;
    private $newDescriptionRepository;
    private $languageRepository;

    public function __construct()
    {
        $this->languageRepository = new LanguageRepository();
        $this->newRepository = new NewRepository();
        $this->newDescriptionRepository = new NewDescriptionRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null)
    {
        $data['newList'] = $this->newRepository->getAll($sort);

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
        $data['languages'] = $this->languageRepository->getAll();
        $data['newList'] = $this->newRepository->getAll($sort);

        return $data;
    }

    public function create($data)
    {
        $newId = $this->newRepository->create($data);

        foreach ($data['description'] as $description) {
            $this->newDescriptionRepository->create($newId, $description);
        }

        return $newId;
    }

    public function getShowUpdatePageData($id)
    {
        $new = $this->newRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['new'] = $this->newDescriptionRepository->getByIdAndLanguageId($new['id'],
                $language['id']);
        }

        $data['new'] = $new;
        $data['languages'] = $languages;

        return $data;
    }

    public function update($file, $data)
    {
        $this->newRepository->updateById($data);

        foreach ($data['description'] as $blogDescription) {
            $productDescriptionExist = $this->newDescriptionRepository->getByIdAndLanguageId($data['id'],
                $blogDescription['language_id']);

            if (empty($productDescriptionExist)) {
                $this->newDescriptionRepository->create($data['id'], $blogDescription);
            } else {
                $this->newDescriptionRepository->updateById($blogDescription);
            }
        }
    }

    public function getShowDeletePageData($id)
    {
        $data['new'] = $this->newRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $this->newRepository->deleteById($id);
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
        return $this->newRepository->getFileByEntityId($id);
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
                'name' => $params['name-' . $language['id']],
                'description' => $params['description-' . $language['id']],
            ];
        }

        return [
            'id' => $params['id'],
            'file_id' => $params['file_id'],
            'description' => $paramsDescription,
        ];
    }
}