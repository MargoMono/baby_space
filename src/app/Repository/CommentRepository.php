<?php

namespace App\Repository;

use PDO;
use PDOException;

class CommentRepository extends AbstractRepository
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $sql = 'SELECT * FROM comment 
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT *
            FROM comment 
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
INSERT INTO comment
    (user_name, user_email, description, status) 
VALUES 
    ( :user_name, :user_email, :description, :status) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':user_name', $data['user_name']);
        $result->bindParam(':user_email', $data['user_email']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':status', $data['status']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function updateById($data)
    {
        $sql = '
UPDATE comment
    SET
    user_email = :user_email,
    user_name = :user_name,
    description = :description,
    status = :status
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':user_email', $data['user_email']);
        $result->bindParam(':user_name', $data['user_name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM comment WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function getFilesByCommentId($id)
    {
        $sql = '
        SELECT 
            c.*, f.alias AS file_alias, f.id AS file_id
        FROM comment c
            JOIN comment_file cf ON c.id = cf.comment_id
            JOIN file f ON cf.file_id = f.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createFilesConnection($commentId, $fileId)
    {
        $sql = '
INSERT INTO comment_file
    (comment_id, file_id) 
VALUES 
    (:comment_id, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_id', $commentId);
        $result->bindParam(':file_id', $fileId);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$commentId, $fileId]);
            throw new \RuntimeException('Unable to create product');
        }
    }

    public function deleteFileConnection($commentId, $fileId)
    {
        $sql = 'DELETE FROM comment_file WHERE comment_id = :comment_id AND file_id =:file_d';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_id', $commentId);
        $result->bindParam(':file_d', $fileId);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$commentId, $fileId]);
            throw new \RuntimeException('Unable to delete product-file connection');
        }
    }

    public function getAnswerByCommentId($commentId)
    {
        $sql = '
        SELECT *
            FROM comment_answer 
        WHERE comment_id = :comment_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_id', $commentId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function createAnswer($data)
    {
        $sql = '
INSERT INTO comment_answer
    (comment_id, description) 
VALUES 
    ( :comment_id, :description) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_id', $data['comment_id']);
        $result->bindParam(':description', $data['description']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function updateAnswerById($data)
    {
        $sql = '
UPDATE comment_answer
    SET
    description = :description
WHERE comment_id = :comment_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':comment_id', $data['comment_id']);

        return $result->execute();
    }
}
