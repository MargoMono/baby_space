<?php

namespace App\Repository;

use App\Components\Repository;
use PDO;

class RoleRepository extends Repository
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

