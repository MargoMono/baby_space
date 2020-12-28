<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class CommentRepository extends AbstractRepository
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'c.id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT c.*, cd.description
            FROM comment c
            JOIN comment_description cd ON c.id = cd.comment_id
        WHERE cd.language_id = :language_id
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
        SELECT *
            FROM comment 
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data): ?string
    {
        $sql = '
        INSERT INTO comment
            (user_name, user_email, status) 
        VALUES 
            ( :user_name, :user_email, :status) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':user_name', $data['user_name']);
        $result->bindParam(':user_email', $data['user_email']);
        $result->bindParam(':status', $data['status']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create comment');
        }
    }

    public function updateById($data): void
    {
        $sql = '
        UPDATE comment
            SET
            user_email = :user_email,
            user_name = :user_name,
            status = :status
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':user_email', $data['user_email']);
        $result->bindParam(':user_name', $data['user_name']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update comment');
        }
    }

    public function deleteById($id): void
    {
        $sql = 'DELETE FROM comment WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete comment');
        }
    }

    public function getFilesByCommentId($id): array
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

    public function createFilesConnection($commentId, $fileId): ?string
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
            throw new \RuntimeException('Unable to create comment files connection');
        }
    }

    public function deleteFileConnection($commentId, $fileId): void
    {
        $sql = 'DELETE FROM comment_file WHERE comment_id = :comment_id AND file_id =:file_d';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_id', $commentId);
        $result->bindParam(':file_d', $fileId);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$commentId, $fileId]);
            throw new \RuntimeException('Unable to delete comment-file connection');
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

    public function createAnswer($data): ?string
    {
        $sql = '
        INSERT INTO comment_answer
            (comment_id) 
        VALUES 
            ( :comment_id)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_id', $data['comment_id']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create comment answer');
        }
    }

    public function getAnswerFilesByAnswerCommentId($id): array
    {
        $sql = '
        SELECT 
            ca.*, f.alias AS file_alias, f.id AS file_id
        FROM comment_answer ca
            JOIN comment_answer_file caf ON ca.id = caf.comment_answer_id
            JOIN file f ON caf.file_id = f.id
        WHERE ca.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createCommentAnswerFile($id, $fileId): ?string
    {
        $sql = '
        INSERT INTO comment_answer_file
            (comment_answer_id, file_id) 
        VALUES 
            (:comment_answer_id, :file_id)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_answer_id', $id);
        $result->bindParam(':file_id', $fileId);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$id, $fileId]);
            throw new \RuntimeException('Unable to create product');
        }
    }

    public function deleteAnswerFileConnection($commentAnswerId, $fileId): void
    {
        $sql = 'DELETE FROM comment_answer_file WHERE comment_answer_id = :comment_answer_id AND file_id =:file_d';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_answer_id', $commentAnswerId);
        $result->bindParam(':file_d', $fileId);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$commentAnswerId, $fileId]);
            throw new \RuntimeException('Unable to delete product-file connection');
        }
    }
}
