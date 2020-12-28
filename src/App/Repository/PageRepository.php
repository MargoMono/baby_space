<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class PageRepository extends AbstractRepository implements Entity
{
    const DELIVERY_PAGE_ID = 1;
    const COMPANY_PAGE_ID = 2;

    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
              p.*, pd.description
        FROM page p
            JOIN page_description pd on p.id = pd.page_id
        WHERE language_id = :language_id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT 
              p.*, pd.description
        FROM page p
            JOIN page_description pd on p.id = pd.page_id
        WHERE p.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
    }

    public function updateById($data)
    {
    }

    public function deleteById($id)
    {
    }

    public function getFileByEntityId($id)
    {
    }

    public function getByIdAndLanguageId($id, $languageId)
    {
        $sql = '
        SELECT 
              p.*, pd.description
        FROM page p
            JOIN page_description pd on p.id = pd.page_id
        WHERE p.id = :id
        AND pd.language_id = :language_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}
