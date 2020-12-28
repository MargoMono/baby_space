<?php

namespace App\Models\Admin;

use App\Repository\LanguageRepository;
use App\Repository\TypeDescriptionRepository;
use App\Repository\TypeRepository;

class TypeModel implements ModelStrategy
{
    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     * @var TypeRepository
     */
    private $typeRepository;

    /**
     * @var TypeDescriptionRepository
     */
    private $typeDescriptionRepository;

    public function __construct()
    {
        $this->languageRepository = new LanguageRepository();
        $this->typeRepository = new TypeRepository();
        $this->typeDescriptionRepository = new TypeDescriptionRepository();
    }

    public function getFileDirectory()
    {
        return null;
    }

    public function getIndexData($sort = null): array
    {
        $data['typeList'] = $this->typeRepository->getAll($sort);

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
        $data['typeList'] = $this->typeRepository->getAll($sort);

        return $data;
    }

    public function create($data): int
    {
        $newEntityId = $this->typeRepository->create($data);

        foreach ($data['description'] as $description) {
            $this->typeDescriptionRepository->create($newEntityId, $description);
        }

        return $newEntityId;
    }

    public function getShowUpdatePageData($id): array
    {
        $type = $this->typeRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['type'] = $this->typeDescriptionRepository->getByIdAndLanguageId($type['id'],
                $language['id']);
        }

        $data['type'] = $type;
        $data['languageList'] = $languages;

        return $data;
    }

    public function update($file, $data): void
    {
        $this->typeRepository->updateById($data);

        foreach ($data['description'] as $blogDescription) {
            $productDescriptionExist = $this->typeDescriptionRepository->getByIdAndLanguageId($data['id'],
                $blogDescription['language_id']);

            if (empty($productDescriptionExist)) {
                $this->typeDescriptionRepository->create($data['id'], $blogDescription);
            } else {
                $this->typeDescriptionRepository->updateById($blogDescription);
            }
        }
    }

    public function getShowDeletePageData($id): array
    {
        $data['type'] = $this->typeRepository->getById($id);

        return $data;
    }

    public function delete($id): void
    {
        $this->typeRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
    }

    public function deleteFileConnection($id, $imageId)
    {
    }

    public function getFile($id)
    {
        return $this->typeRepository->getFileByEntityId($id);
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
