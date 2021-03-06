<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class TypeRepository extends AbstractRepository implements Entity
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
              t.*, td.name
        FROM type t 
            JOIN type_description td on t.id = td.type_id
        WHERE language_id = :language_id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
         SELECT 
              t.*, td.name
        FROM type t 
            JOIN type_description td on t.id = td.type_id
        WHERE t.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
        $sql = '
        INSERT INTO type
            () 
        VALUES 
            () ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create size');
        }
    }

    public function updateById($data){
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM type WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete size');
        }
    }

    public function getFileByEntityId($id)
    {
    }

    public function getAllByParams($params = null)
    {
        $languageId = $params['language_id'] ?? Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
              t.*, td.name
        FROM type t 
            JOIN type_description td on t.id = td.type_id
        WHERE language_id = :language_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}
