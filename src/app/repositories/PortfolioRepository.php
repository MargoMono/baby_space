<?php

namespace App\Repository;

use App\Repository\Repository;
use PDO;

class PortfolioRepository extends Repository
{
    public function getPhotoById($id)
    {
        $sql = '
        SELECT p.*, f.alias AS file_alias
        FROM portfolio p
        LEFT JOIN file f ON p.file_id = f.id
        WHERE p.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getPortfolioList($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT 
            p.*, f.alias AS file_alias
        FROM portfolio p
            LEFT JOIN file f ON p.file_id = f.id
        ORDER BY ' . $order . ' 
        ASC';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createPortfolio($data)
    {
        $sql = '
INSERT INTO portfolio
    (name, description, file_id, created_at) 
VALUES 
    (:name, :description, :file_id, now()) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':file_id', $data['file_id']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function updatePortfolio($data)
    {
        $sql = '
UPDATE portfolio
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

    public function deletePhotoById($id)
    {
        $sql = 'DELETE FROM portfolio WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function getLastPhotos($count)
    {
        $sql = "
        SELECT 
            p.*, f.alias AS file_alias
        FROM portfolio p
            JOIN file f ON p.file_id = f.id
        ORDER BY p.created_at DESC 
        LIMIT " . $count;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getMorePhotos($count, $limit)
    {
        $sql = '
        SELECT 
            p.*, f.alias AS file_alias
        FROM portfolio p
            JOIN file f ON p.file_id = f.id
        ORDER BY p.created_at DESC 
        LIMIT ' . $limit . ' OFFSET ' . $count;

        $result = $this->db->prepare($sql);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->bindParam(':offset', $count, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}
