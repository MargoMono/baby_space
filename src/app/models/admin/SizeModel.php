<?php

namespace App\Models\Admin;

use App\Helpers\TextHelper;
use App\Repository\SizeRepository;

class SizeModel implements ModelStrategy
{
    private $sizeRepository;

    public function __construct()
    {
        $this->sizeRepository = new SizeRepository();
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
        $data['sizeList'] = $this->sizeRepository->getAll($sort);

        return $data;
    }

    public function create($data): int
    {
        return $this->sizeRepository->create($data);
    }

    public function getShowUpdatePageData($id): array
    {
        $data['size'] = $this->sizeRepository->getById($id);

        return $data;
    }

    public function update($file, $data): void
    {
        $this->sizeRepository->updateById($data);
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
        return [
            'id' => $params['id'],
            'name' => $params['name'],
        ];
    }
}
