<?php

namespace App\Repository;

use PDO;

class BlogRepository extends AbstractRepository implements Entity
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'b.id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $sql = '
        SELECT b.*, f.alias AS file_alias
            FROM blog b
            LEFT JOIN file f ON b.file_id = f.id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
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
    (name, short_description, description, file_id, alias, tag, meta_title, meta_description, meta_keyword) 
VALUES 
    (:name, :short_description, :description, :file_id, :alias, :tag, :meta_title, :meta_description, :meta_keyword) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':short_description', $data['short_description']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':tag', $data['tag']);
        $result->bindParam(':meta_title', $data['meta_title']);
        $result->bindParam(':meta_description', $data['meta_description']);
        $result->bindParam(':meta_keyword', $data['meta_keyword']);

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
    short_description = :short_description,
    description = :description,
    file_id = :file_id,
    alias = :alias,
    tag = :tag,
    meta_title = :meta_title,
    meta_description = :meta_description,
    meta_keyword = :meta_keyword
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':short_description', $data['short_description']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':tag', $data['tag']);
        $result->bindParam(':meta_title', $data['meta_title']);
        $result->bindParam(':meta_description', $data['meta_description']);
        $result->bindParam(':meta_keyword', $data['meta_keyword']);
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

    public function getFileByEntityId($id)
    {
        $sql = '
        SELECT f.*
            FROM blog b
            LEFT JOIN file f ON b.file_id = f.id
        WHERE b.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
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

}
