<?php

namespace App\Repository;

use App\Repository\Repository;
use PDO;

class SeoRepository extends Repository
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
