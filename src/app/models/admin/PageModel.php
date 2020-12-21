<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\LanguageRepository;
use App\Repository\PageDescriptionRepository;
use App\Repository\PageRepository;

class PageModel implements ModelStrategy
{
    public $fileDirectory = 'page';

    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * @var PageDescriptionRepository
     */
    private $pageDescriptionRepository;

    public function __construct()
    {
        $this->languageRepository = new LanguageRepository();
        $this->pageRepository = new PageRepository();
        $this->pageDescriptionRepository = new PageDescriptionRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null): array
    {
        $data['pageList'] = $this->pageRepository->getAll($sort);

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
    }

    public function create($data)
    {
    }

    public function getShowUpdatePageData($id): array
    {
        $blog = $this->pageRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['page'] = $this->pageDescriptionRepository->getByIdAndLanguageId($blog['id'],
                $language['id']);
        }

        $data['page'] = $blog;
        $data['languageList'] = $languages;

        return $data;
    }

    public function update($file, $data): void
    {
        foreach ($data['description'] as $blogDescription) {
            $productDescriptionExist = $this->pageDescriptionRepository->getByIdAndLanguageId($data['id'],
                $blogDescription['language_id']);

            if (empty($productDescriptionExist)) {
                $this->pageDescriptionRepository->create($data['id'], $blogDescription);
            } else {
                $this->pageDescriptionRepository->updateById($blogDescription);
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
        return $this->pageRepository->getFileByEntityId($id);
    }

    public function getFiles($id)
    {
        return null;
    }

    public function prepareData($params): array
    {
        $paramsDescription = [];

        foreach ($this->languageRepository->getAll() as $language) {
            $paramsDescription[$language['id']] = [
                'language_id' => $language['id'],
                'id' => $params['id-' . $language['id']],
                'description' => $params['description-' . $language['id']],
            ];
        }

        return [
            'id' => $params['id'],
            'description' => $paramsDescription,
        ];
    }
}
