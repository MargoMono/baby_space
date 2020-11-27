<?php

namespace App\Repository;

use PDO;
use PDOException;

class RateRepository extends AbstractRepository
{
    public function getAll($sort = null)
    {
        $sql = '
        SELECT r.*, c.code
            FROM rate r
        JOIN currency c on r.currency_id = c.id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $sort);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function create($data)
    {
        $sql = '
INSERT INTO rate
    (currency_id, rate, date) 
VALUES 
    (:currency_id, :rate, :date) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':currency_id', $data['currency_id']);
        $result->bindParam(':rate', $data['rate']);
        $result->bindParam(':date', $data['date']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create product');
        }
    }

    public function updateById($data)
    {
        $sql = '
UPDATE rate
    SET
    currency_id = :currency_id,
    rate = :rate,
    date = :date
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':currency_id', $data['currency_id']);
        $result->bindParam(':rate', $data['rate']);
        $result->bindParam(':date', $data['date']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }
}
