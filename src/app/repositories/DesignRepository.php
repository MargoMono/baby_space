<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class DesignRepository extends AbstractRepository
{
    public function getDesignById($id)
    {
        $sql = '
        SELECT 
            *
        FROM design 
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getDesignList($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT 
            * 
        FROM design 
        ORDER BY ' . $order . ' 
        ASC';

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $order);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createDesign($data)
    {
        $sql = '
INSERT INTO design
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

    public function createFileDesignConnection($designId, $fileId)
    {
        $sql = '
INSERT INTO design_file
    (design_id, file_id) 
VALUES 
    (:design_id, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':design_id', $designId);
        $result->bindParam(':file_id', $fileId);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function getDesignPhotos($id)
    {
        $sql = "
        SELECT 
             f.alias AS file_alias, f.id AS file_id, f.name AS file_name
        FROM design_file cf
            LEFT JOIN file f ON cf.file_id = f.id
        WHERE cf.design_id = :id";

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function updateDesign($data)
    {
        $sql = '
UPDATE design
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

    public function deleteFileDesignConnection($designId, $fileId)
    {
        $sql = 'DELETE FROM design_file WHERE design_id = :design_id AND file_id =:file_d';

        $result = $this->db->prepare($sql);
        $result->bindParam(':design_id', $designId);
        $result->bindParam(':file_d', $fileId);

        return $result->execute();
    }

    public function deleteDesignById($id)
    {
        $sql = 'DELETE FROM design WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }
}
