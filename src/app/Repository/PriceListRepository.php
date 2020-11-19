<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class PriceListRepository extends AbstractRepository
{
    public function getPriceById($id)
    {
        $sql = '
        SELECT 
            pl.*, f.alias AS file_alias
        FROM price_list pl
            LEFT JOIN file f ON pl.file_id = f.id
        WHERE pl.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getPriceList($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT 
            pl.*, f.alias AS file_alias
        FROM price_list pl
            LEFT JOIN file f on pl.file_id = f.id
        ORDER BY ' . $order . ' 
        ASC';

        $result = $this->db->prepare($sql);

        $result->bindParam(':order', $order);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createPrice($data)
    {
        $sql = '
INSERT INTO price_list
    (name, file_id) 
VALUES 
    (:name, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':file_id', $data['file_id']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function updatePrice($data)
    {
        $sql = '
UPDATE price_list
    SET
    name = :name,
    file_id = :file_id
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function deletePriceById($id)
    {
        $sql = 'DELETE FROM price_list WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }
}
