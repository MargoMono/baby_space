<?php

namespace App\Controllers\Admin;

use App\Models\Admin\ModelContext;
use App\Models\Admin\ModelStrategy;
use App\View\View;

class ControllerContext
{
    /**
     * @var ModelStrategy
     */
    private $modelStrategy;

    /**
     * @var ModelContext
     */
    private $modelContext;

    /**
     * @var string
     */
    private $viewDirectory;

    /**
     * @var View
     */
    private $view;

    public function __construct(ModelStrategy $modelStrategy, ModelContext $modelContext, string $viewDirectory)
    {
        $this->modelStrategy = $modelStrategy;
        $this->modelContext = $modelContext;
        $this->viewDirectory = $viewDirectory;

        $this->view = new View();
    }

    public function actionIndex()
    {
        $data = $this->modelStrategy->getIndexData($_GET);
        $this->view->generate("admin/$this->viewDirectory/index.twig", $data);
    }

    public function actionShowCreatePage()
    {
        $data = $this->modelStrategy->getShowCreatePageData();
        $this->view->generate("admin/$this->viewDirectory/create.twig", $data);
    }

    public function create()
    {
        try {
            $this->modelContext->create($_FILES, $_POST);
        } catch (\Exception $exception) {
            $this->errorAction('добавить');
            return;
        }

        $this->successAction('добавлено');
    }

    public function actionShowUpdatePage($id)
    {
        $data = $this->modelStrategy->getShowUpdatePageData($id);
        $this->view->generate("admin/$this->viewDirectory/update.twig", $data);
    }

    public function update()
    {
        try {
            $this->modelContext->update($_FILES, $_POST);
        } catch (\Exception $exception) {
            $this->errorAction('обновить');
            return;
        }

        $this->successAction('отредактировано');
    }

    public function actionShowDeletePage($id)
    {
        $data = $this->modelStrategy->getShowDeletePageData($id);
        $this->view->generate("admin/$this->viewDirectory/delete.twig", $data);
    }

    public function delete()
    {
        try {
            $this->modelContext->delete($_POST);
        } catch (\Exception $exception) {
            $this->errorAction('удалить');
            return;
        }

        $this->successAction('удалено');
    }

    /**
     * @param $action
     */
    public function successAction($action): void
    {
        $data = $this->modelStrategy->getIndexData();
        $data['success'] = "Успешно $action";
        $this->view->generate("admin/$this->viewDirectory/index.twig", $data);
    }

    /**
     * @param $action
     */
    public function errorAction($action): void
    {
        $data = $this->modelStrategy->getIndexData();
        $data['error_warning'] = "Невозможно $action, обратитесь к разработчику";
        $this->view->generate("admin/$this->viewDirectory/index.twig", $data);
    }
}