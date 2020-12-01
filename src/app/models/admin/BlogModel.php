<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\BlogRepository;

class BlogModel implements ModelStrategy
{
    public $fileDirectory = 'blog';

    private $blogRepository;

    public function __construct()
    {
        $this->blogRepository = new BlogRepository();
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
        $data['blogList'] = $this->blogRepository->getAll($sort);

        return $data;
    }

    public function create($data): int
    {
        return $this->blogRepository->create($data);
    }

    public function getShowUpdatePageData($id): array
    {
        $data['blog'] = $this->blogRepository->getById($id);

        return $data;
    }

    public function update($file, $data): void
    {
        $this->blogRepository->updateById($data);
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
        return [
            'id' => $params['id'],
            'name' => $params['name'],
            'short_description' => $params['short_description'],
            'description' => $params['description'],
            'file_id' => $params['file_id'],
            'alias' => TextHelper::getTranslit($params['name']),
            'tag' => $params['tag'],
            'meta_title' => $params['meta_title'],
            'meta_description' => $params['meta_description'],
            'meta_keyword' => $params['meta_keyword'],
        ];
    }
}
