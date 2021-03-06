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
            status = :status
        WHERE id = :id';

        $result = $this->db->prepare($sql);
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

    public function getByLanguageId($languageId)
    {
        $sql = '
        SELECT *
            FROM sale s
            JOIN sale_description sd on s.id = sd.sale_id
        WHERE sd.language_id = :language_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}
