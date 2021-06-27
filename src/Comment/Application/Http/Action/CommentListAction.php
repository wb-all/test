<?php

declare(strict_types=1);

namespace App\Comment\Application\Http\Action;

use App\Comment\Infrastructure\Repository\CommentRepositoryInterface;
use App\Common\Application\Http\AbstractAction;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

final class CommentListAction extends AbstractAction
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $topicId = (int) $request->getAttribute('id');

        $comments = $this->commentRepository->findAllByTopic($topicId);

        return $this->json([
            'result' => $this->buildTreeComments($comments),
        ]);
    }

    // @todo: There will be a memory leak if there are many comments
    private function buildTreeComments(array $comments, int $parentId = 0): array
    {
        $result = [];

        foreach ($comments as $comment) {
            if ($parentId === (int) $comment['parent_id']) {
                $result[] = [
                    'id' => $comment['id'],
                    'parent_id' => $comment['parent_id'],
                    'topic_id' => $comment['topic_id'],
                    'body' => $comment['body'],
                    'created_at' => $comment['created_at'],
                    'children' => $this->buildTreeComments($comments, $comment['id'])
                ];
            }
        }

        return $result;
    }
}
