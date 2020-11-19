<?php

namespace App\Models\Admin;

interface Strategy
{
    public function getFileDirectory(): string;

    public function getIndexData($order = null);

    public function getShowCreatePageData($order = null);

    public function validation($file, $params);

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
