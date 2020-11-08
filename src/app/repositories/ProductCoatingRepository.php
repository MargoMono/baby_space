<?php

namespace App\Repository\Site;

use App\Repository\Repository;
use PDO;

class ProductCoatingRepository extends Repository
{
    public function getProductCoatingListByProductId($id)
    {
        $sql = '
        SELECT 
            pc.*, c.name AS coating_name, c.description AS coating_description
        FROM product_coating pc
            JOIN coating c ON pc.coating_id = c.id
        WHERE pc.product_id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createProductCoating($productId, $coatingId)
    {
        $sql = '
INSERT INTO product_coating 
    (product_id, coating_id) 
VALUES 
    (:product_id, :coating_id)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':coating_id', $coatingId);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function updateProductById($data)
    {
        $sql = '
UPDATE product
    SET
    category_id = :category_id,
    name = :name,
    description = :description,
    file_id = :file_id,
    enabled = :enabled,
    alias = :alias,
    position = :position,
    tag_title = :tag_title,
    tag_description = :tag_description,
    tag_keywords = :tag_keywords
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $data['category_id']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':enabled', $data['enabled']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':position', $data['position']);
        $result->bindParam(':tag_title', $data['tag_title']);
        $result->bindParam(':tag_description', $data['tag_description']);
        $result->bindParam(':tag_keywords', $data['tag_keywords']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function deleteProductCoating($id)
    {
        $sql = '
        DELETE FROM 
            product_coating 
        WHERE 
            id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }
}

