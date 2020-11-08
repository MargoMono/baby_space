<?php

namespace App\Repository;

use App\Repository\Repository;
use PDO;

class PriceListOrderRepository extends Repository
{
    public function getClientList($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT 
            * 
        FROM price_list_order 
        ORDER BY ' . $order . ' 
        ASC';

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $order);
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
