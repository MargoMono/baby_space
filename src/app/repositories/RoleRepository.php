<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class RoleRepository extends AbstractRepository
{
    const ADMIN = 100;
    const MANAGER = 50;

    public function getRoleList()
    {
        $sql = '
        SELECT 
            * 
        FROM role';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getRoleById($id)
    {
        $sql = '
        SELECT 
            * 
        FROM role
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}

