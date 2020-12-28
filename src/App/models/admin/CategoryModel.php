<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\CategoryDescriptionRepository;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\ProductRepository;

class CategoryModel implements ModelStrategy
{
    public $fileDirectory = 'category';

    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var CategoryDescriptionRepository
     */
    private $categoryDescriptionRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct()
    {
        $this->languageRepository = new LanguageRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->categoryDescriptionRepository = new CategoryDescriptionRepository();
        $this->productRepository = new ProductRepository();
    }

    public function getFileDirectory(): string
    {
        return $this->fileDirectory;
    }

    public function getIndexData($sort = null): array
    {
        $data['categoryList'] = $this->categoryRepository->getAll($sort);

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
        $data['categoryList'] = $this->categoryRepository->getAll($sort);

        return $data;
    }

    public function create($data): int
    {
        $newEntityId = $this->categoryRepository->create($data);

        foreach ($data['description'] as $description) {
            $this->categoryDescriptionRepository->create($newEntityId, $description);
        }

        return $newEntityId;
    }

    public function getShowUpdatePageData($id): array
    {
        $category = $this->categoryRepository->getById($id);
        $languages = $this->languageRepository->getAll();

        foreach ($languages as $key => $language) {
            $languages[$key]['category'] = $this->categoryDescriptionRepository->getByIdAndLanguageId($category['id'],
                $language['id']);
        }

        $data['category'] = $category;
        $data['languageList'] = $languages;

        return $data;
    }

    public function update($file, $data): void
    {
        $this->categoryRepository->updateById($data);

        foreach ($data['description'] as $description) {
            $productDescriptionExist = $this->categoryDescriptionRepository->getByIdAndLanguageId($data['id'],
                $description['language_id']);

            if (empty($productDescriptionExist)) {
                $this->categoryDescriptionRepository->create($data['id'], $description);
            } else {
                $this->categoryDescriptionRepository->updateById($description);
            }
        }
    }

    public function getShowDeletePageData($id): array
    {
        $data['productExist'] = empty($this->productRepository->getAllByCategoryId($id)) ? false : true;
        $data['category'] = $this->categoryRepository->getById($id);

        return $data;
    }

    public function delete($id): void
    {
        $this->categoryRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
    }

    public function deleteFileConnection($id, $imageId)
    {
    }

    public function getFile($id)
    {
        return $this->categoryRepository->getFileByEntityId($id);
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
            ];
        }

        return [
            'id' => $params['id'],
            'parent_id' => $params['parent_id'],
            'file_id' => $params['file_id'],
            'status' => $params['status'],
            'alias' => TextHelper::getTranslit($params['name-1']),
            'description' => $paramsDescription,
        ];
    }
}
