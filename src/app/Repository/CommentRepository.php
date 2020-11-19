<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class CommentRepository extends AbstractRepository
{
    public function getCommentById($id)
    {
        $sql = '
        SELECT 
            *
        FROM comment
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function publishCommentById($id)
    {
        $sql = '
UPDATE 
    comment    
    SET
    allow = 1
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function getCommentList($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT c.*, cp.id AS parent_id
        FROM comment c
            LEFT JOIN comment cp ON c.parent_id = cp.id
        ORDER BY c.' . $order . ' 
        ASC';

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $order);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createComment($data)
    {
        $sql = '
INSERT INTO comment
    (parent_id, user_name, user_email, description, allow, content, created_at) 
VALUES 
    (:parent_id, :user_name, :user_email, :description, :allow, :content, now()) ';

        $allow = (int)$data['allow'];

        $result = $this->db->prepare($sql);
        $result->bindParam(':parent_id', $data['parent_id']);
        $result->bindParam(':user_name', $data['user_name']);
        $result->bindParam(':user_email', $data['user_email']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':allow', $allow);
        $result->bindParam(':content', $data['content']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function updateNew($data)
    {
        $sql = '
UPDATE blog
    SET
    name = :name,
    description = :description,
    content = :content,
    file_id = :file_id,
    alias = :alias,
    tag_title = :tag_title,
    tag_description = :tag_description,
    tag_keywords = :tag_keywords
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':content', $data['content']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':tag_title', $data['tag_title']);
        $result->bindParam(':tag_description', $data['tag_description']);
        $result->bindParam(':tag_keywords', $data['tag_keywords']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function deleteCommentById($id)
    {
        $sql = 'DELETE FROM comment WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function getLastAllowedComments($count)
    {
        $sql = "
        SELECT 
            *
        FROM comment
        WHERE allow = 1
        ORDER BY created_at DESC
        LIMIT " . $count;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getCommentPhotos($id)
    {
        $sql = "
        SELECT 
             f.alias AS file_alias
        FROM comment_file cf
            LEFT JOIN file f ON cf.file_id = f.id
        WHERE cf.comment_id = :id";

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getLimitCommentPhotos($id, $count)
    {
        $sql = "
        SELECT 
           f.alias AS file_alias
        FROM comment_file cf
            LEFT JOIN file f ON cf.file_id = f.id
        WHERE cf.comment_id = :id   
        LIMIT " . $count;

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getAllAllowedComments()
    {
        $sql = '
        SELECT 
            * 
        FROM comment
        WHERE allow = 1';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getMoreAllowedComments($count, $limit)
    {
        $sql = '
        SELECT *
            FROM comment 
        WHERE allow = 1
        ORDER BY created_at DESC 
        LIMIT ' . $limit . ' OFFSET ' . $count;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createFileCommentConnection($commentId, $fileId)
    {
        $sql = '
INSERT INTO comment_file
    (comment_id, file_id) 
VALUES 
    (:comment_id, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_id', $commentId);
        $result->bindParam(':file_id', $fileId);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function getChildCommentListById($id)
    {
        $sql = '
        SELECT cp.*
            FROM comment c
            LEFT JOIN comment cp ON cp.parent_id = c.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getLastComments($count)
    {
        $sql = "
        SELECT 
            *
        FROM comment 
        WHERE allow = 1
        AND parent_id IS NULL
        ORDER BY created_at 
        LIMIT " . $count;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getClientByEmail($userEmail)
    {
        $sql = '
        SELECT 
            * 
        FROM comment 
        WHERE 
            user_email = :user_email
        ORDER BY created_at DESC ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':user_email', $userEmail);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}
