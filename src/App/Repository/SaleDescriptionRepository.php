<?php

namespace App\Repository;

use PDO;
use PDOException;

class SaleDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($saleId, $languageId)
    {
        $sql = '
        SELECT 
           *
        FROM sale_description
        WHERE sale_id = :sale_id
        AND language_id = :language_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':sale_id', $saleId);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$saleId, $languageId]);
            throw new \RuntimeException('Unable to load sale description');
        }
    }

    public function create($saleId, $data)
    {
        $sql = '
INSERT INTO sale_description
    (sale_id, language_id, name, sale, description) 
VALUES 
    (:sale_id, :language_id, :name, :sale, :description) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':sale_id', $saleId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':sale', $data['sale']);
        $result->bindParam(':description', $data['description']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create sale description');
        }
    }

    public function updateById($data)
    {
        $sql = '
UPDATE sale_description
    SET
    name = :name,
    sale = :sale,
    description = :description
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':sale', $data['sale']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update sale description');
        }
    }
}

