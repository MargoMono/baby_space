<?php

namespace App\Repository;

use PDO;
use PDOException;

class LanguageRepository extends AbstractRepository implements Entity
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
        SELECT l.* , f.alias AS file_alias
            FROM language l
            LEFT JOIN file f ON l.file_id = f.id
        ORDER BY '. $sort['order'].' '. $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT l.*, f.alias AS file_alias
            FROM language l
            LEFT JOIN file f ON l.file_id = f.id
        WHERE l.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
        $sql = '
        INSERT INTO language
            (name, alias, code, file_id) 
        VALUES 
            (:name, :alias, :code, :file_id)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':code', $data['code']);
        $result->bindParam(':file_id', $data['file_id']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create language');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE language
            SET
            name = :name,
            alias = :alias,
            code = :code,
            file_id = :file_id
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':code', $data['code']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update language');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM language WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete language');
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
