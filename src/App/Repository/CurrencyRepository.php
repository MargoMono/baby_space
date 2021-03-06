<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class CurrencyRepository extends AbstractRepository
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $sql = '
        SELECT c.* , r.rate
        FROM currency c
           LEFT JOIN rate r on c.id = r.currency_id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $sort);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT *
        FROM currency 
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
        $sql = '
        INSERT INTO currency
            (name, alias, code) 
        VALUES 
            (:name, :alias, :code) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':code', $data['code']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create currency');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE currency
            SET
            name = :name,
            alias = :alias,
            code = :code
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':code', $data['code']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update currency');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM currency WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete currency');
        }
    }

    public function getAllCurrencyForConvert()
    {
        $sql = "
        SELECT 
            c.*, r.rate
        FROM currency c
            LEFT JOIN rate r on c.id = r.currency_id
        WHERE code != 'RUB'";

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getByParams($params = null)
    {
        $where = '';

        if (!empty($params['id'])) {
            $where .= " c.id = '{$params['id']}'";
        }

        $sql = "
          SELECT 
            c.*, r.rate
        FROM currency c 
            LEFT JOIN rate r on c.id = r.currency_id
        WHERE {$where}";

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}
