<?php

declare(strict_types=1);

namespace App\Comment\Infrastructure\Service;

use App\Comment\Infrastructure\Dto\CommentDto;

class CommentService
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    // @todo: better to return entity and check on comment created
    public function create(CommentDto $dto): void
    {
        $sql = "INSERT INTO comments (parent_id, topic_id, body, created_at) VALUES (:parentId, :topicId, :body, NOW())";

        $statement = $this->db->prepare($sql);
        $statement->execute([':parentId' => $dto->parentId, ':topicId' => $dto->topicId, ':body' => $dto->body]);
    }
}
