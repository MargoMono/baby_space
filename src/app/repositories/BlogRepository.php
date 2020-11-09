<?php

namespace App\Repository;

use PDO;

class BlogRepository extends AbstractRepository implements Repository
{
    public function getAll($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT * 
        FROM blog n
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
        SELECT b.*, f.alias AS file_alias
        FROM blog b
        LEFT JOIN file f ON b.file_id = f.id
        WHERE b.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
        $sql = '
INSERT INTO blog
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
UPDATE blog
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
        $sql = 'DELETE FROM blog WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function getLastArticles($count)
    {
        $sql = "
        SELECT b.*, f.alias AS file_alias
        FROM blog b
            LEFT JOIN file f ON b.file_id = f.id
        ORDER BY b.created_at 
        LIMIT " . $count;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getAllArticles()
    {
        $sql = 'SELECT * FROM blog';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getMoreArticles($count, $limit)
    {
        $sql = '
        SELECT b.*, f.alias AS file_alias
        FROM blog b
            LEFT JOIN file f ON b.file_id = f.id
        ORDER BY b.created_at  
        LIMIT ' . $limit . ' OFFSET ' . $count;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createFilesConnection($categoryId, $fileId)
    {

    }
}
