<?php

declare(strict_types=1);

namespace App\Comment\Infrastructure\Repository;

class CommentRepository implements CommentRepositoryInterface
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function findAllByTopic(int $topicId): array
    {
        $sql = 'SELECT * FROM comments where topic_id = :topicId';

        $sth = $this->db->prepare($sql);
        $sth->execute([':topicId' => $topicId]);

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function exists(int $commentId): bool
    {
        $sth = $this->db->query('SELECT COUNT(*) FROM comments where id = ' . $commentId);

        return $sth->fetchColumn() >= 1;
    }
}
