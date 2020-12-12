<?php

namespace App\Controllers\Admin;

use App\Components\Logger;
use App\Exceptions\AdminException;
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

    private $logger;

    public function __construct(ModelStrategy $modelStrategy, ModelContext $modelContext, $viewDirectory)
    {
        $this->modelStrategy = $modelStrategy;
        $this->modelContext = $modelContext;
        $this->viewDirectory = $viewDirectory;
        $this->logger = Logger::getLogger(static::class);

        $this->view = new View();
    }

    public function actionIndex()
    {
        $data = $this->modelStrategy->getIndexData($_GET);

        if (!empty($_SESSION['success'])) {
            $data['success'] = 'Успешно ' . $_SESSION['success'];
            unset($_SESSION['success']);
        }

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
        } catch (AdminException $exception) {
            $this->logger->error($exception->getMessage(), array_merge($_FILES, $_POST));
            $this->errorAction('добавить', $exception->getMessage());
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), array_merge($_FILES, $_POST));
            $this->errorAction('добавить');
        }

        $this->successAction('добавлено');
    }

    public function actionShowUpdatePage($id)
    {
        $data = $this->modelStrategy->getShowUpdatePageData($id);

        if (!empty($_SESSION['success'])) {
            $data['success'] = $_SESSION['success'];
            unset($_SESSION['success']);
        }

        if (!empty($_SESSION['error_warning'])) {
            $data['error_warning'] = $_SESSION['error_warning'];
            unset($_SESSION['error_warning']);
        }

        $this->view->generate("admin/$this->viewDirectory/update.twig", $data);
    }

    public function update()
    {
        $id = $_POST['id'];

        try {
            $this->modelContext->update($_FILES, $_POST);
            $_SESSION['success'] = 'Успешно отредактировано';
            header("Location: /admin/$this->viewDirectory/update/$id");
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), array_merge($_FILES, $_POST));
            $_SESSION['error_warning'] = 'Не удалось отредактировать, обратитесь к разработчику';
            header("Location: /admin/$this->viewDirectory/update/$id");
        }
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
            $this->logger->error($exception->getMessage(), array_merge($_FILES, $_POST));
            $this->errorAction('удалить');
            return;
        }

        $this->successAction('удалено');
    }

    public function imageDelete($id, $imageId)
    {
        try {
            $this->modelContext->imageDelete($id, $imageId);
            $_SESSION['success'] = 'Изображение удалено успешно';
            header("Location: /admin/$this->viewDirectory/update/$id");
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), ['id' => $id, 'image_id' => $imageId]);
            $_SESSION['error_warning'] = 'Не удалось удалить изображение, обратитесь к разработчику';
            header("Location: /admin/$this->viewDirectory/update/$id");
        }
    }

    /**
     * @param $action
     */
    public function successAction($action): void
    {
        $_SESSION['success'] = $action;
        header("Location: /admin/$this->viewDirectory");
    }

    /**
     * @param $action
     */
    public function errorAction($action, $explain = null): void
    {
        $data = $this->modelStrategy->getIndexData();

        if(!empty($explain)){
            $data['error_warning'] = "Невозможно $action - $explain";
        } else {
            $data['error_warning'] = "Невозможно $action, обратитесь к разработчику.";
        }

        $this->view->generate("admin/$this->viewDirectory/index.twig", $data);
    }

    public function actionFilter()
    {
        $data = $this->modelStrategy->getFilteredData($_POST);
        echo json_encode($data);
    }
}