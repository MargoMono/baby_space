<?php

namespace App\Repository;

use PDO;
use PDOException;

class CouponRepository extends AbstractRepository implements Entity
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
            FROM coupon 
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT *
            FROM coupon 
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
        INSERT INTO coupon
            (code, discount, quantity, start_date, end_date) 
        VALUES 
            (:code, :discount, :quantity, :start_date, :end_date)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':code', $data['code']);
        $result->bindParam(':discount', $data['discount']);
        $result->bindParam(':quantity', $data['quantity']);
        $result->bindParam(':start_date', $data['start_date']);
        $result->bindParam(':end_date', $data['end_date']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create coupon');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE coupon
            SET
            code = :code,
            discount = :discount,
            quantity = :quantity,
            start_date = :start_date,
            end_date = :end_date
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':code', $data['code']);
        $result->bindParam(':discount', $data['discount']);
        $result->bindParam(':quantity', $data['quantity']);
        $result->bindParam(':start_date', $data['start_date']);
        $result->bindParam(':end_date', $data['end_date']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update coupon');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM coupon WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete coupon');
        }
    }

    public function getFileByEntityId($id)
    {
        return null;
    }
}
