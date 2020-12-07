<?php

namespace App\Repository;

use PDO;
use PDOException;

class SaleRepository extends AbstractRepository implements Entity
{
    public function getAll($sort = null)
    {
    }

    public function getById($id)
    {
        $sql = '
        SELECT *
            FROM sale 
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE sale
            SET
            sale = :sale,
            name = :name,
            description = :description,
            status = :status
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':sale', $data['sale']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update sale');
        }
    }

    public function deleteById($id)
    {
    }

    public function getFileByEntityId($id)
    {
    }
}
