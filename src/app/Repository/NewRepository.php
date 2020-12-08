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
               n.* , nd.*, f.alias AS file_alias
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
        $sql = '
        SELECT n.*, f.alias AS file_alias
        FROM new n
        LEFT JOIN file f ON n.file_id = f.id
        WHERE n.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
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

    public function getLastNewList($count)
    {
        $sql = '
        SELECT n.*, f.alias AS file_alias
        FROM new n
            LEFT JOIN file f ON n.file_id = f.id
        ORDER BY n.created_at 
        LIMIT ' . $count;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getAllNewList()
    {
        $sql = 'SELECT * FROM new';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getMoreNews($count, $limit)
    {
        $sql = '
        SELECT n.*, f.alias AS file_alias
        FROM new n
            LEFT JOIN file f ON n.file_id = f.id
        ORDER BY n.created_at  
        LIMIT ' . $limit . ' OFFSET ' . $count;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getLastNew()
    {
        $sql = '
        SELECT n.*, f.alias AS file_alias
        FROM new n
        LEFT JOIN file f ON n.file_id = f.id
        ORDER BY n.created_at DESC
        LIMIT 1';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
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
}
