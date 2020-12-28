<?php

namespace App\Repository;

use PDO;
use PDOException;

class CountryDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($countryId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM country_description
        WHERE country_id = :country_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':country_id', $countryId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [
                'country_id' => $countryId,
                'language_id' => $languageIg
            ]);
            throw new \RuntimeException('Unable to load country description');
        }
    }

    public function create($countryId, $data)
    {
        $sql = '
        INSERT INTO country_description
            (country_id, language_id, name) 
        VALUES 
            (:country_id, :language_id, :name) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':country_id', $countryId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':name', $data['name']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create country description');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE country_description
            SET
            name = :name
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update country description');
        }
    }
}

