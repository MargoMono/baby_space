<?php

namespace App\Models\Admin;

use App\Models\Models;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;

class UserModel extends Model
{
    public function getIndexData($order)
    {
        $userRepository = new UserRepository();
        $userList = $userRepository->getUserList($order);

        $data['userList'] = $userList;
        $data['is_admin'] = $this->checkIfRoleIsAdmin($_SESSION['user']);
        $data['user_id'] = $_SESSION['user']['id'];

        return $data;
    }

    public function getShowCreatePageData()
    {
        $roleRepository = new RoleRepository();
        $data['roleList'] = $roleRepository->getRoleList();

        return $data;
    }

    public function create($params)
    {
        $res['result'] = false;

        $userRepository = new UserRepository();

        if (!empty($userRepository->getUserByEmail($params['email']))) {
            $res['errors'][] = 'Пользователь с такой почтой уже существует';
            return $res;
        }

        $salt = $this->salt();

        $data = [
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => md5(md5($_POST['password']) . $salt),
            'salt' => $salt,
            'active_hex' => md5($salt),
            'role_id' => $params['role_id'],
        ];

        $newUser = $userRepository->createUser($data);

        if (empty($newUser)) {
            $res['errors'][] = 'Ошибка создания нового пользователя';
            return $res;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowUpdatePageData($id)
    {
        $categoryRepository = new UserRepository();
        $user = $categoryRepository->getUserById($id);

        $roleRepository = new RoleRepository();
        $roleList = $roleRepository->getRoleList();

        foreach ($roleList as $key => $role) {
            if ($role['id'] == $user['role_id']) {
                $roleList[$key]['selected'] = true;
                continue;
            }
            $roleList[$key]['selected'] = false;
        }

        $data['roleList'] = $roleList;
        $data['user'] = $user;

        return $data;
    }

    public function update($params)
    {
        $res['result'] = false;

        $userRepository = new UserRepository();

        if (!$userRepository->updateUser($params)) {
            $res['errors'][] = 'Ошибка обновления пользователя';
            return $res;
        }

        $activeUser = $userRepository->getUserById($_SESSION["user"]['id']);

        if ($activeUser['id'] == $params['id']) {
            unset($_SESSION["user"]);
            $_SESSION['user'] = $activeUser;
        }

        $res['result'] = true;
        return $res;
    }

    public function getShowDeletePageData($id)
    {
        $categoryRepository = new UserRepository();
        $data['user'] = $categoryRepository->getUserById($id);

        return $data;
    }

    public function delete($data)
    {
        $res['result'] = false;

        $categoryRepository = new UserRepository();

        if ($categoryRepository->deleteUserById($data['id'])) {
            $res['result'] = true;
            return $res;
        }

        $res['errors'][] = "ошибка при удалении пользователя";

        return $res;
    }

    private function salt()
    {
        return substr(md5(uniqid()), -8);
    }

    private function checkIfRoleIsAdmin($user)
    {
        $roleRepository = new RoleRepository();
        $role = $roleRepository->getRoleById($user['role_id']);

        return $role['permission'] == RoleRepository::ADMIN;
    }
}

