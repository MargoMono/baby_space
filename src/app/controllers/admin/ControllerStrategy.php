<?php

namespace App\Controllers\Admin;

interface ControllerStrategy
{
    public function actionIndex();

    public function actionShowCreatePage();

    public function create();

    public function actionShowUpdatePage($id);

    public function update();

    public function actionShowDeletePage($id);

    public function delete();
}
