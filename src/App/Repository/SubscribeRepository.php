<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class SubscribeRepository extends AbstractRepository
{
    public function create($data): ?string
    {
        $sql = '
        INSERT INTO subscribe
            (user_name, email, status)
        VALUES
            ( :email, :status) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':email', $data['email']);
        $result->bindParam(':status', $data['status']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to subscribe');
        }
    }
}
