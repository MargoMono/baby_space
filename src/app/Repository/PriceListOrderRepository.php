<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class PriceListOrderRepository extends AbstractRepository
{
    public function getClientList($sort = null)
    {
        if (empty($sort)) {
            $sort = 'id';
        }

        $sql = '
        SELECT 
            * 
        FROM price_list_order 
        ORDER BY ' . $sort . ' 
        ASC';

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $sort);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function create($data)
    {
        $sql = '
INSERT INTO price_list_order 
    (name, company, email, created_at) 
VALUES 
    (:name, :company, :email, now()) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':company', $data['company']);
        $result->bindParam(':email', $data['email']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function getClientByEmail($email)
    {
        $sql = '
        SELECT 
            * 
        FROM price_list_order 
        WHERE 
            email = :email
        ORDER BY created_at DESC ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':email', $email);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}
