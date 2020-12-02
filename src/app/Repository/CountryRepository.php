<?php

namespace App\Repository;

use PDO;
use PDOException;

class CountryRepository extends AbstractRepository implements Entity
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
        SELECT c.* , f.alias AS file_alias, cr.name as currency_name
            FROM country c
            LEFT JOIN file f ON c.file_id = f.id
            LEFT JOIN currency cr ON c.currency_id = cr.id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $sort);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT c.*, f.alias AS file_alias, cr.name as currency_name
            FROM country c
            LEFT JOIN file f ON c.file_id = f.id
            LEFT JOIN currency cr ON c.currency_id = cr.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
        $sql = '
        INSERT INTO country
            (name, alpha2, alpha3, status, file_id, currency_id) 
        VALUES 
            (:name, :alpha2, :alpha3, :status, :file_id, :currency_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':alpha2', $data['alpha2']);
        $result->bindParam(':alpha3', $data['alpha3']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':currency_id', $data['currency_id']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create country');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE country
            SET
            name = :name,
            alpha2 = :alpha2,
            alpha3 = :alpha3,
            status = :status,
            file_id = :file_id,
            currency_id = :currency_id
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':alpha2', $data['alpha2']);
        $result->bindParam(':alpha3', $data['alpha3']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':currency_id', $data['currency_id']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update country');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM country WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete country');
        }
    }

    public function getFileByEntityId($id)
    {
        $sql = '
        SELECT f.*
            FROM language l
            LEFT JOIN file f ON l.file_id = f.id
        WHERE l.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}
