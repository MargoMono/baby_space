<?php

namespace App\Models\Admin;

use App\Repository\TypeRepository;

class TypeModel implements ModelStrategy
{
    private $typeRepository;

    public function __construct()
    {
        $this->typeRepository = new TypeRepository();
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
        $data['typeList'] = $this->typeRepository->getAll($sort);

        return $data;
    }

    public function create($data): int
    {
        return $this->typeRepository->create($data);
    }

    public function getShowUpdatePageData($id): array
    {
        $data['type'] = $this->typeRepository->getById($id);

        return $data;
    }

    public function update($file, $data): void
    {
        $this->typeRepository->updateById($data);
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
        return [
            'id' => $params['id'],
            'name' => $params['name'],
        ];
    }
}
