<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
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

        $sql = '
        SELECT n.* , f.alias AS file_alias
            FROM new n
            LEFT JOIN file f ON n.file_id = f.id
        ORDER BY '. $sort['order'].' '. $sort['desc'];

        $result = $this->db->prepare($sql);
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
    (name, description, file_id) 
VALUES 
    (:name, :description, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
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
    name = :name,
    description = :description,
    file_id = :file_id
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM new WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function getLastNewList($count)
    {
        $sql = "
        SELECT n.*, f.alias AS file_alias
        FROM new n
            LEFT JOIN file f ON n.file_id = f.id
        ORDER BY n.created_at 
        LIMIT " . $count;

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

    public function createFilesConnection($id, $fileId)
    {
        // TODO: Implement createFilesConnection() method.
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
