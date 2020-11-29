<?php

namespace App\Models\Admin;

interface ModelStrategy
{
    public function getFileDirectory(): string;

    public function getIndexData($sort = null): array;

    public function getShowCreatePageData($sort = null): array;

    public function create($data): int;

    public function getShowUpdatePageData($id): array;

    public function update($data): void;

    public function getShowDeletePageData($id): array;

    public function delete($id): void;

    // Work with data

    public function prepareData($params): array;

    public function getFile($id);

    public function getFiles($id);

    public function createFilesConnection($id, $fileId);

    public function deleteFileConnection($id, $imageId);
}
