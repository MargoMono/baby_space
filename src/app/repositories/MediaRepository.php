<?php

namespace App\Repository;

use App\Repository\Repository;
use PDO;

class MediaRepository extends Repository
{
    public function getGalleryImages($galleryId)
    {
        $sql = '
        SELECT 
               mm.provider_reference AS media_name
        FROM media__gallery_media mgm 
            JOIN media__media mm ON mgm.media_id = mm.id
        WHERE gallery_id = :gallery_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':gallery_id', $galleryId, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}
