<?php

namespace App\Repository\Site;

use App\Components\Repository;
use PDO;

class ProductPageKindRepository extends Repository
{
    const COATING_PAGE_ID = 1;
    const DESIGN_PAGE_ID = 2;

    public function getEnableProductPageKind()
    {
        $sql = '
        SELECT 
            *
        FROM product_page_kind 
        WHERE enabled = 1';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function updateProductPageKind($pageKindId)
    {
        if ($pageKindId == self::COATING_PAGE_ID) {
            $coatingValue = 1;
            $designValue = 0;
        } else {
            $designValue = 1;
            $coatingValue = 0;
        }

        $sql = '
    UPDATE product_page_kind ppk1 
        JOIN product_page_kind ppk2 ON ppk1.id = 1 AND ppk2.id = 2
    SET 
        ppk1.enabled = :coating_value,
        ppk2.enabled = :design_value
';

        $result = $this->db->prepare($sql);
        $result->bindParam(':coating_value', $coatingValue);
        $result->bindParam(':design_value', $designValue);

        return $result->execute();
    }
}

