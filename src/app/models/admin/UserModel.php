<?php

namespace App\Models\Admin;

use App\Exceptions\AdminException;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;

class UserModel implements ModelStrategy
{
    private $userRepository;
    private $roleRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
    }

    public function getFileDirectory(): string
    {
        return null;
    }

    public function getIndexData($sort = null)
    {
        $data['userList'] = $this->userRepository->getAll($sort);
        $data['is_admin'] = $this->checkIfRoleIsAdmin($_SESSION['user']);
        $data['user_id'] = $_SESSION['user']['id'];

        if ($sort['desc'] == 'DESC') {
            $sort['desc'] = 'ASC';
        } else {
            $sort['desc'] = 'DESC';
        }

        $data['sort'] = $sort;

        return $data;
    }

    public function getShowCreatePageData($sort = null)
    {
        $data['roleList'] = $this->roleRepository->getRoleList();
        return $data;
    }

    public function create($data)
    {
        if ($data['password'] !== $data['password_confirm']) {
            throw new AdminException(AdminException::USER_PASSWORD_CONFIRM_ERROR);
        }

        if (!empty($this->userRepository->getUserByEmail($data['email']))) {
            throw new AdminException(AdminException::USER_ALREADY_EXIST);
        }

        return $this->userRepository->create($data);
    }

    public function getShowUpdatePageData($id)
    {
        $data['roleList'] = $this->roleRepository->getRoleList();
        $data['user'] = $this->userRepository->getById($id);

        return $data;
    }

    public function update($file, $data)
    {
        $this->userRepository->update($data);

        $activeUser = $this->userRepository->getById($_SESSION["user"]['id']);

        if ($activeUser['id'] == $data['id']) {
            unset($_SESSION['user']);
            $_SESSION['user'] = $activeUser;
        }
    }

    public function getShowDeletePageData($id)
    {
        $data['user'] = $this->userRepository->getById($id);

        return $data;
    }

    public function delete($id)
    {
        $this->userRepository->deleteById($id);
    }

    public function createFilesConnection($id, $fileId)
    {
        return null;
    }

    public function deleteFileConnection($id, $imageId)
    {
        return null;
    }

    public function getFile($id)
    {
        return null;
    }

    public function getFiles($id)
    {
        return null;
    }

    public function prepareData($params)
    {
        $salt = substr(md5(uniqid('', true)), -8);

        return [
            'id' => $params['id'],
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => md5(md5($_POST['password']) . $salt),
            'password_confirm' => md5(md5($_POST['password_confirm']) . $salt),
            'salt' => $salt,
            'active_hex' => md5($salt),
            'role_id' => $params['role_id'],
        ];
    }

    private function checkIfRoleIsAdmin($user)
    {
        $roleRepository = new RoleRepository();
        $role = $roleRepository->getRoleById($user['role_id']);

        return $role['permission'] == RoleRepository::ADMIN;
    }
}