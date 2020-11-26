<?php

namespace App\Repository;

use PDO;

class PageRepository extends AbstractRepository implements Entity
{
    const COMPANY_PAGE_ID = 1;
    const DELIVERY_PAGE_ID = 2;

    public function getById($id)
    {
        $sql = '
        SELECT 
            p.*, f.alias AS file_alias
        FROM page p
            LEFT JOIN file f on p.file_id = f.id
        WHERE p.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getAll($sort = null)
    {
        if (empty($sort)) {
            $sort = 'id';
        }

        $sql = '
        SELECT * 
        FROM page 
        ORDER BY ' . $sort . ' 
        ASC';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function updateById($data)
    {
        $sql = '
UPDATE page
    SET
    content = :content,
    file_id = :file_id,
    tag_title = :tag_title,
    tag_description = :tag_description,
    tag_keywords = :tag_keywords
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':content', $data['content']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':tag_title', $data['tag_title']);
        $result->bindParam(':tag_description', $data['tag_description']);
        $result->bindParam(':tag_keywords', $data['tag_keywords']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function create($data)
    {
        // TODO: Implement create() method.
    }

    public function deleteById($id)
    {
        // TODO: Implement create() method.
    }

    public function createFilesConnection($id, $fileId)
    {
        // TODO: Implement createFilesConnection() method.
    }

    public function getFileByEntityId($id)
    {
        // TODO: Implement getFileByEntityId() method.
    }
}
