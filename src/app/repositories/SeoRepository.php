<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class SeoRepository extends AbstractRepository
{
    public function getSeoTags($route)
    {
        $db = Db::getConnection();

        $sql = 'SELECT * FROM seo WHERE route = :route';

        $result = $db->prepare($sql);
        $result->bindParam(':route', $route, PDO::PARAM_STR);

        $result->execute();

        return $result->fetch();
    }
}

?>
