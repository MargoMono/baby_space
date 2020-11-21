<?php

namespace App\Models\Admin;

interface ModelStrategy
{
    public function getFileDirectory(): string;

    public function getIndexData($sort = null);

    public function getShowCreatePageData($sort = null);

    public function create($data);

    public function getShowUpdatePageData($id);

    public function update($data);

    public function getShowDeletePageData($id);

    public function delete($id);

    // Work with data

    public function prepareData($params);

    public function getFile($id);

    public function getFiles($id);
}
