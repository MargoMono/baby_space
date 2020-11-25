<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class ProductRecommendationsRepository extends AbstractRepository
{
    public function getProductRecommendationsIdsByProductId($id)
    {
        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
            p.* , f.alias AS file_alias, pd.description as description, pd.name as product_name
        FROM product_recommendations pr
            JOIN product p ON pr.recommendation_id = p.id
            JOIN file f ON p.file_id = f.id
            JOIN product_description pd ON p.id = pd.product_id
        WHERE pr.product_id = :id
        AND  pd.language_id = :language_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function create($productId, $recommendationId)
    {
        $sql = '
INSERT INTO product_recommendations 
    (product_id, recommendation_id) 
VALUES 
    (:product_id, :recommendation_id)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':recommendation_id', $recommendationId);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            throw new \RuntimeException('Unable to create product recommendations');
        }
    }

    public function deleteByProductId($productId)
    {
        $sql = 'DELETE FROM product_recommendations WHERE product_id = :product_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            throw new \RuntimeException('Unable to delete product recommendations');
        }
    }
}

