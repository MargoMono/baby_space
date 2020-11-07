<?php

namespace App\Repository\Site;

use App\Components\Repository;
use PDO;

class ProductRecommendationsRepository extends Repository
{
    public function getProductRecommendationsIdsByProductId($id)
    {
        $sql = '
        SELECT 
            p.* , f.alias AS file_alias
        FROM product_recommendations pr
            JOIN product p ON pr.recommendation_id = p.id
            JOIN file f ON p.file_id = f.id
        WHERE pr.product_id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getProductList($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT 
            p.*, f.alias AS file_alias, c.name AS category_name
        FROM product p
            JOIN file f ON p.file_id = f.id 
            JOIN category c ON p.category_id = c.id 
        ORDER BY ' . $order;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getProductListByCategory($categoryId, $limit)
    {
        $sql = '
        SELECT 
            p.*, f.alias as file_alias
        FROM product p 
            JOIN category c ON p.category_id = c.id 
            JOIN file f ON p.file_id = f.id 
        WHERE p.enabled = 1 
            AND p.category_id = :category_id 
        ORDER BY p.position ASC 
        LIMIT :limit';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createProductRecommendations($productId, $recommendationId)
    {
        $sql = '
INSERT INTO product_recommendations 
    (product_id, recommendation_id) 
VALUES 
    (:product_id, :recommendation_id)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':recommendation_id', $recommendationId);

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

    public function deleteProductRecommendations($recommendationId, $productId)
    {
        $sql = '
        DELETE FROM 
            product_recommendations 
        WHERE 
            recommendation_id = :recommendation_id 
            AND product_id = :product_id ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':recommendation_id', $recommendationId);
        $result->bindParam(':product_id', $productId);

        return $result->execute();
    }
}

