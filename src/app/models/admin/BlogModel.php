<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\BlogDescriptionRepository;
use App\Repository\BlogRepository;
use App\Repository\LanguageRepository;

class BlogModel implements ModelStrategy
{
    public $fileDirectory = 'blog';

    private $blogRepository;
    private $blogDescriptionRepository;
    private $languageRepository;

    public function __construct()
    {
        $this->blogRepository = new BlogRepository();
        $this->languageRepository = new LanguageRepository();
        $this->blogDescriptionRepository = new BlogDescriptionRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null): array
    {
        $data['blogList'] = $this->blogRepository->getAll($sort);

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
        $data['languages'] = $this->languageRepository->getAll();
        $data['blogList'] = $this->blogRepository->getAll($sort);

        return $data;
    }

    public function create($data): int
    {
        $newEntityId = $this->blogRepository->create($data);

        foreach ($data['description'] as $description) {
            $this->blogDescriptionRepository->create($newEntityId, $description);
        }

        return $newEntityId;
    }

    public function getShowUpdatePageData($id): array
    {
        $blog = $this->blogRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['blog'] = $this->blogDescriptionRepository->getByIdAndLanguageId($blog['id'],
                $language['id']);
        }

        $data['blog'] = $blog;
        $data['languages'] = $languages;

        return $data;
    }

    public function update($file, $data): void
    {
        $this->blogRepository->updateById($data);

        foreach ($data['description'] as $blogDescription) {
            $productDescriptionExist = $this->blogDescriptionRepository->getByIdAndLanguageId($data['id'],
                $blogDescription['language_id']);

            if (empty($productDescriptionExist)) {
                $this->blogDescriptionRepository->create($data['id'], $blogDescription);
            } else {
                $this->blogDescriptionRepository->updateById($blogDescription);
            }
        }
    }

    public function getShowDeletePageData($id): array
    {
        $data['blog'] = $this->blogRepository->getById($id);

        return $data;
    }

    public function delete($id): void
    {
        $this->blogRepository->deleteById($id);
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
        return $this->blogRepository->getFileByEntityId($id);
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
                'short_description' => $params['short_description-' . $language['id']],
                'description' => $params['description-' . $language['id']],
                'meta_title' => $params['meta_title-' . $language['id']],
                'meta_description' => $params['meta_description-' . $language['id']],
                'meta_keyword' => $params['meta_keyword-' . $language['id']],
                'tag' => $params['tag-' . $language['id']],
            ];
        }

        return [
            'id' => $params['id'],
            'file_id' => $params['file_id'],
            'alias' => TextHelper::getTranslit($params['name-1']),
            'description' => $paramsDescription,
        ];
    }
}
