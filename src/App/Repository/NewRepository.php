<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class NewRepository extends AbstractRepository implements Entity
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'n.id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
               n.* ,  f.alias AS file_alias,
               nd.description AS description, nd.name AS name
        FROM new n
            JOIN file f ON n.file_id = f.id
            JOIN new_description nd ON n.id = nd.new_id
        WHERE nd.language_id = :language_id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
               n.* , f.alias AS file_alias,
               nd.description AS description, nd.name AS name
        FROM new n
            JOIN file f ON n.file_id = f.id
            JOIN new_description nd ON n.id = nd.new_id
        WHERE nd.language_id = :language_id
        AND n.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$id, $languageId]);
            throw new \RuntimeException('Unable to get new by id');
        }
    }

    public function create($data)
    {
        $sql = '
        INSERT INTO new
            (file_id) 
        VALUES 
            (:file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':file_id', $data['file_id']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create new');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE new
            SET
            file_id = :file_id
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update new');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM new WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete new');
        }
    }

    public function getFileByEntityId($id)
    {
        $sql = '
        SELECT f.*
            FROM new n
            LEFT JOIN file f ON n.file_id = f.id
        WHERE n.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getAllByParams($params = null, $limit = null, $offset = null)
    {
        $languageId = $params['language_id'] ?? Language::DEFAUL_LANGUGE_ID;

        $limitAndOffset = '';

        if (!empty($limit)) {
            $limitAndOffset .= ' LIMIT ' . $limit;

            if (!empty($offset)) {
                $limitAndOffset .= ' OFFSET ' . $offset;
            }
        }

        $sql = '
        SELECT 
               n.*, f.alias AS file_alias,
               nd.description as description, nd.name as name
        FROM new n
            JOIN file f ON n.file_id = f.id
            JOIN new_description nd ON n.id = nd.new_id
        WHERE nd.language_id = :language_id
        ORDER BY n.created_at DESC ' . $limitAndOffset;

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}
