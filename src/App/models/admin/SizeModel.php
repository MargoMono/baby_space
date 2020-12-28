<?php

namespace App\Models\Admin;

use App\Repository\LanguageRepository;
use App\Repository\SizeDescriptionRepository;
use App\Repository\SizeRepository;

class SizeModel implements ModelStrategy
{
    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     * @var SizeRepository
     */
    private $sizeRepository;

    /**
     * @var SizeDescriptionRepository
     */
    private $sizeDescriptionRepository;

    public function __construct()
    {
        $this->languageRepository = new LanguageRepository();
        $this->sizeRepository = new SizeRepository();
        $this->sizeDescriptionRepository = new SizeDescriptionRepository();
    }

    public function getFileDirectory()
    {
        return null;
    }

    public function getIndexData($sort = null): array
    {
        $data['sizeList'] = $this->sizeRepository->getAll($sort);

        if ($sort['desc'] == 'DESC') {
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;

        return $data;
    }

    public function getShowCreatePageData($sort = null): array
    {
        $data['languageList'] = $this->languageRepository->getAll();
        $data['sizeList'] = $this->sizeRepository->getAll($sort);

        return $data;
    }

    public function create($data): int
    {
        $newEntityId = $this->sizeRepository->create($data);

        foreach ($data['description'] as $description) {
            $this->sizeDescriptionRepository->create($newEntityId, $description);
        }

        return $newEntityId;
    }

    public function getShowUpdatePageData($id): array
    {
        $type = $this->sizeRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['size'] = $this->sizeDescriptionRepository->getByIdAndLanguageId($type['id'],
                $language['id']);
        }

        $data['size'] = $type;
        $data['languageList'] = $languages;

        return $data;
    }

    public function update($file, $data): void
    {
        $this->sizeRepository->updateById($data);

        foreach ($data['description'] as $blogDescription) {
            $productDescriptionExist = $this->sizeDescriptionRepository->getByIdAndLanguageId($data['id'],
                $blogDescription['language_id']);

            if (empty($productDescriptionExist)) {
                $this->sizeDescriptionRepository->create($data['id'], $blogDescription);
            } else {
                $this->sizeDescriptionRepository->updateById($blogDescription);
            }
        }
    }

    public function getShowDeletePageData($id): array
    {
        $data['size'] = $this->sizeRepository->getById($id);

        return $data;
    }

    public function delete($id): void
    {
        $this->sizeRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
    }

    public function deleteFileConnection($id, $imageId)
    {
    }

    public function getFile($id)
    {
        return $this->sizeRepository->getFileByEntityId($id);
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
            'description' => $paramsDescription,
        ];
    }
}
