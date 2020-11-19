<?php

namespace App\Repository;

use PDO;

class SearchRepository extends AbstractRepository
{
    public  function getIndexData($search)
    {
        $sql = '
        SELECT 
            p.*, f.alias AS file_alias
        FROM product p
            JOIN file f ON p.file_id = f.id
        WHERE p.name LIKE "%' . $search . '%" OR p.description LIKE "%' . $search . '%"';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}
