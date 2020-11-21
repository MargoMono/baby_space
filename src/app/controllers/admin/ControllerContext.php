<?php

namespace App\Controllers\Admin;

use App\Models\Admin\ModelContext;
use App\Models\Admin\ModelStrategy;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\NewRepository;
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

        $defaultData = $this->getDefaultData();
        $this->view = new View($defaultData);
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

        header("Location: /admin/$this->viewDirectory");
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

        header("Location: /admin/$this->viewDirectory");
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

        header("Location: /admin/$this->viewDirectory");
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

    public function getDefaultData()
    {
        $categoryRepository = new CategoryRepository();
        $mainCategoryList = $categoryRepository->getMainCategoryList();

        foreach ($mainCategoryList as $key => $mainCategory) {
            $mainCategoryList[$key]['childCategoryList'] = $categoryRepository->getEnableChildCategoryListById($mainCategory['id']);
        }

        if (!empty($_SESSION['comparison_product'])) {
            $data['comparison_product_count'] = count($_SESSION['comparison_product']);
        } else {
            $data['comparison_product_count'] = 0;
        }

        $newRepository = new NewRepository();
        $lastNew = $newRepository->getLastNew();

        $data['lastNew'] = $lastNew;
        $data['footerCategoryList'] = $mainCategoryList;

        $languagesRepository = new LanguageRepository();
        $data['languages'] = $languagesRepository->getAll();

        return $data;
    }
}