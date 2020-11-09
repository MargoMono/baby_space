<?php

namespace App\Model\Admin;

interface Strategy
{
    // Work with pages

    public function getIndexData($order = null);

    public function getShowCreatePageData($order = null);

    public function create($data);

    public function getShowUpdatePageData($id);

    public function update($data);

    public function getShowDeletePageData($id);

    public function delete($id);


    // Work with files

    public function addFileConnection($file);

    public function addFilesConnection($files, $id);

    public function createFilesConnection($id, $fileId);

    public function updateFileConnection($file, $params);

    public function updateFilesConnection($files, $id);

    public function deleteFileConnection($id, $photoId): bool;


    // Work with data

    public function prepareData($params);
}
