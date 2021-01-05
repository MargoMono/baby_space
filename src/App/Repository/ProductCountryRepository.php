<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class ProductCountryRepository extends AbstractRepository
{
    public function getProductCountryIdsByProductId($id)
    {
        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
             cd.name, c.id
        FROM product_country pc
            JOIN product p ON pc.product_id = p.id
            JOIN product_description pd ON p.id = pd.product_id
            JOIN country c ON pc.country_id = c.id
            JOIN country_description cd ON c.id = cd.country_id
        WHERE pc.product_id = :id
        AND  pd.language_id = :language_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function create($productId, $countryId)
    {
        $sql = '
INSERT INTO product_country 
    (product_id, country_id) 
VALUES 
    (:product_id, :country_id)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':country_id', $countryId);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            throw new \RuntimeException('Unable to create product country');
        }
    }

    public function deleteByProductId($productId)
    {
        $sql = 'DELETE FROM product_country WHERE product_id = :product_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            throw new \RuntimeException('Unable to delete product country');
        }
    }
}

