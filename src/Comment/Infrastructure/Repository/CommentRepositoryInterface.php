<?php

declare(strict_types=1);

namespace App\Comment\Infrastructure\Repository;

interface CommentRepositoryInterface
{
    public function findAllByTopic(int $topicId): array;

    public function exists(int $commentId): bool;
}