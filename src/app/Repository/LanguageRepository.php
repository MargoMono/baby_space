<?php

namespace App\Repository;

use PDO;

class LanguageRepository extends AbstractRepository implements Repository
{
    public function getAll($order = null)
    {
        if (empty($order)) {
            $order = 'l.id';
        }

        $sql = '
        SELECT l.* , f.alias AS file_alias
        FROM language l
        LEFT JOIN file f ON l.file_id = f.id
        ORDER BY ' . $order . ' 
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $order);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getAllId()
    {

        $sql = '
        SELECT id
        FROM language 
        ';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT b.*, f.alias AS file_alias
        FROM language b
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
INSERT INTO language
    (name, alias, code, file_id) 
VALUES 
    (:name, :alias, :code, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':code', $data['code']);
        $result->bindParam(':file_id', $data['file_id']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
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

        return $result->execute();
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM language WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function createFilesConnection($id, $fileId)
    {
        // TODO: Implement createFilesConnection() method.
    }
}
