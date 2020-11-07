<?php

namespace App\Repository\Site;

use App\Components\Repository;
use PDO;

class ProductDesignRepository extends Repository
{
    public function getProductDesignListByProductId($id)
    {
        $sql = '
        SELECT 
            pc.*, c.name AS design_name, c.description AS design_description
        FROM product_design pc
            JOIN design c ON pc.design_id = c.id
        WHERE pc.product_id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createProductDesign($productId, $designId)
    {
        $sql = '
INSERT INTO product_design 
    (product_id, design_id) 
VALUES 
    (:product_id, :design_id)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':design_id', $designId);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function deleteProductDesign($id)
    {
        $sql = '
        DELETE FROM 
            product_design 
        WHERE 
            id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }
}

