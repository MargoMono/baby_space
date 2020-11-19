<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class NewRepository extends AbstractRepository implements Repository
{
    public function getAll($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT * 
        FROM new 
        ORDER BY ' . $order . ' 
        ASC';

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $order);
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
    (name, description, content, file_id, alias, tag_title, tag_description, tag_keywords, created_at) 
VALUES 
    (:name, :description, :content, :file_id, :alias, :tag_title, :tag_description, :tag_keywords, now()) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':content', $data['content']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':tag_title', $data['tag_title']);
        $result->bindParam(':tag_description', $data['tag_description']);
        $result->bindParam(':tag_keywords', $data['tag_keywords']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function updateById($data)
    {
        $sql = '
UPDATE new
    SET
    name = :name,
    description = :description,
    content = :content,
    file_id = :file_id,
    alias = :alias,
    tag_title = :tag_title,
    tag_description = :tag_description,
    tag_keywords = :tag_keywords
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':content', $data['content']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':tag_title', $data['tag_title']);
        $result->bindParam(':tag_description', $data['tag_description']);
        $result->bindParam(':tag_keywords', $data['tag_keywords']);
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
}
