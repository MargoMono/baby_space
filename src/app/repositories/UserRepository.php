<?php

namespace App\Repository;

use App\Components\Repository;
use PDO;

class UserRepository extends Repository
{
    const ADMIN = 100;

    public function getUserList($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT 
            u.*, r.name AS role_name 
        FROM user u 
            JOIN role r on u.role_id = r.id
            ORDER BY u.' . $order;

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $order);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getUserByEmailAndPassword($email, $password)
    {
        $sql = "
            SELECT u.*, r.name as role, r.permission
            FROM user u
            LEFT JOIN role r ON u.role_id = r.id
            WHERE u.email = :email
              AND u.password = :password
        ";


        $result = $this->db->prepare($sql);
        $result->bindParam(':email', $email);
        $result->bindParam(':password', $password);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getUserById($id)
    {
        $sql = '
        SELECT 
            * 
        FROM user 
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getUserByEmail($email)
    {
        $sql = '
        SELECT 
            * 
        FROM user 
        WHERE email = :email';

        $result = $this->db->prepare($sql);
        $result->bindParam(':email', $email);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getUserByActiveHex($activeHex)
    {
        $sql = '
        SELECT 
            * 
        FROM user 
        WHERE active_hex = :active_hex';

        $result = $this->db->prepare($sql);
        $result->bindParam(':active_hex', $activeHex);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function changePassword($id, $password)
    {
        $sql = "
        UPDATE 
            user 
        SET 
            password = :password 
        WHERE id = :id";

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->bindParam(':password', $password);

        return $result->execute();
    }

    public function createUser($data)
    {
        $sql = '
        INSERT INTO user 
            (name, email, password, salt, active_hex, role_id) 
        VALUES 
            (:name, :email, :password, :salt, :active_hex, :role_id)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':email', $data['email']);
        $result->bindParam(':password', $data['password']);
        $result->bindParam(':salt', $data['salt']);
        $result->bindParam(':active_hex', $data['active_hex']);
        $result->bindParam(':role_id', $data['role_id']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function updateUserPassword($data)
    {
        $sql = '
        UPDATE user
        SET
            password = :password,
            salt = :salt,
            active_hex = :new_active_hex
        WHERE active_hex = :old_active_hex';

        $result = $this->db->prepare($sql);
        $result->bindParam(':password', $data['password']);
        $result->bindParam(':salt', $data['salt']);
        $result->bindParam(':new_active_hex', $data['new_active_hex']);
        $result->bindParam(':old_active_hex', $data['old_active_hex']);

        return $result->execute();
    }

    public function updateUser($data)
    {
        $sql = '
        UPDATE user
        SET
            name = :name,
            email = :email,
            role_id = :role_id
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':email', $data['email']);
        $result->bindParam(':role_id', $data['role_id']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function deleteUserById($id)
    {
        $sql = 'DELETE FROM user WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }
}

