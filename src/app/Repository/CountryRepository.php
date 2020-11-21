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
        SELECT c.* , f.alias AS file_alias
            FROM country c
            LEFT JOIN file f ON c.file_id = f.id
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
        SELECT c.*, f.alias AS file_alias
            FROM country c
            LEFT JOIN file f ON c.file_id = f.id
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
    (name, iso_code_2, iso_code_3, status, file_id) 
VALUES 
    (:name, :iso_code_2, :iso_code_3, :status, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':iso_code_2', $data['iso_code_2']);
        $result->bindParam(':iso_code_3', $data['iso_code_3']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':file_id', $data['file_id']);

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
    iso_code_2 = :iso_code_2,
    iso_code_3 = :iso_code_3,
    status = :status,
    file_id = :file_id
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':iso_code_2', $data['iso_code_2']);
        $result->bindParam(':iso_code_3', $data['iso_code_3']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM country WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
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

    public function createFilesConnection($id, $fileId)
    {
        // TODO: Implement createFilesConnection() method.
    }
}
