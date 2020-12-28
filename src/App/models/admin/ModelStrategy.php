<?php

namespace App\Models\Admin;

interface ModelStrategy
{
    public function getFileDirectory();

    public function getIndexData($sort = null);

    public function getShowCreatePageData($sort = null);

    public function create($data);

    public function getShowUpdatePageData($id);

    public function update($file, $data);

    public function getShowDeletePageData($id);

    public function delete($id);

    // Work with data

    public function prepareData($params);

    public function getFile($id);

    public function getFiles($id);

    public function createFilesConnection($id, $fileId);

    public function deleteFileConnection($id, $imageId);
}
