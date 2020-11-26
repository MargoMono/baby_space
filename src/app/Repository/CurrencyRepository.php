<?php

namespace App\Repository;

use PDO;
use PDOException;

class CurrencyRepository extends AbstractRepository
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $sql = '
        SELECT * 
        FROM currency 
        ORDER BY '. $sort['order'].' '. $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $sort);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT *
        FROM currency 
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
        $sql = '
INSERT INTO currency
    (name, alias, code, rate) 
VALUES 
    (:name, :alias, :code, :rate) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':code', $data['code']);
        $result->bindParam(':rate', $data['rate']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create product');
        }

        return null;
    }

    public function updateById($data)
    {
        $sql = '
UPDATE currency
    SET
    name = :name,
    alias = :alias,
    code = :code,
    rate = :rate
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':code', $data['code']);
        $result->bindParam(':rate', $data['rate']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM currency WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function getCurrencyForConvert($sort = null)
    {
        $sql = "
        SELECT * 
            FROM currency 
        WHERE code != 'RUB'";

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}
