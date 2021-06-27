<?php

declare(strict_types=1);

namespace App\Comment\Infrastructure\Dto;

class CommentDto
{
    public int $topicId = 0;

    public ?int $parentId = null;

    public string $body = '';
}