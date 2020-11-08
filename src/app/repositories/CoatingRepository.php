<?php

namespace App\Repository;

use App\Repository\Repository;
use PDO;

class CoatingRepository extends Repository
{
    public function getCoatingById($id)
    {
        $sql = '
        SELECT 
            *
        FROM coating 
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getCoatingList($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT 
            * 
        FROM coating 
        ORDER BY ' . $order . ' 
        ASC';

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $order);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createCoating($data)
    {
        $sql = '
INSERT INTO coating
    (name, description) 
VALUES 
    (:name, :description) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function createFileCoatingConnection($coatingId, $fileId)
    {
        $sql = '
INSERT INTO coating_file
    (coating_id, file_id) 
VALUES 
    (:coating_id, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':coating_id', $coatingId);
        $result->bindParam(':file_id', $fileId);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function getCoatingPhotos($id)
    {
        $sql = "
        SELECT 
             f.alias AS file_alias, f.id AS file_id, f.name AS file_name
        FROM coating_file cf
            LEFT JOIN file f ON cf.file_id = f.id
        WHERE cf.coating_id = :id";

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function updateCoating($data)
    {
        $sql = '
UPDATE coating
    SET
    name = :name,
    description = :description
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function deleteFileCoatingConnection($coatingId, $fileId)
    {
        $sql = 'DELETE FROM coating_file WHERE coating_id = :coating_id AND file_id =:file_d';

        $result = $this->db->prepare($sql);
        $result->bindParam(':coating_id', $coatingId);
        $result->bindParam(':file_d', $fileId);

        return $result->execute();
    }

    public function deleteCoatingById($id)
    {
        $sql = 'DELETE FROM coating WHERE id = :id';

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
        ORDER BY b.created_at DESC 
        LIMIT ' . $limit . ' OFFSET ' . $count;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}
