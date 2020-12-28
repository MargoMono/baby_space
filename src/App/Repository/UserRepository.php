<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;
use PDOException;

class UserRepository extends AbstractRepository
{
    const ADMIN = 100;

    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'u.id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $sql = '
        SELECT 
            u.*, r.name AS role_name 
        FROM user u 
            JOIN role r on u.role_id = r.id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];


        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $sort);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
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

    public function create($data)
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

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create user');
        }
    }

    public function update($data)
    {
        $sql = '
        UPDATE user
        SET
            name = :name,
            email = :email,
            password = :password,
            salt = :salt,
            active_hex = :active_hex,
            role_id = :role_id
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':email', $data['email']);
        $result->bindParam(':password', $data['password']);
        $result->bindParam(':salt', $data['salt']);
        $result->bindParam(':active_hex', $data['active_hex']);
        $result->bindParam(':role_id', $data['role_id']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update user');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM user WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete user');
        }
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


}

