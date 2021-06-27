<?php

declare(strict_types=1);

namespace App\Comment\Application\Http\Action;

use App\Comment\Application\Exception\ValidationException;
use App\Comment\Infrastructure\Dto\CommentDto;
use App\Comment\Infrastructure\Repository\CommentRepositoryInterface;
use App\Comment\Infrastructure\Service\CommentService;
use App\Common\Application\Http\AbstractAction;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

final class CommentCreatorAction extends AbstractAction
{
    private CommentService $commentService;
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentService $commentService, CommentRepositoryInterface $commentRepository)
    {
        $this->commentService = $commentService;
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        // Best use Symfony serializer and validator
        try {
            $commentDto = $this->mapOnDto($request);

            $this->validateDto($commentDto);

            $this->commentService->create($commentDto);

            return $this->json([]);
        } catch (ValidationException $e) {
            return $this->json(
                [
                    'message' => 'Validation error.',
                    'errors' => [
                        [
                            'propertyPath' => $e->getPropertyPath(),
                            'message' => $e->getErrorMessage(),
                        ],
                    ],
                ],
                422
            );
        } catch (\RuntimeException $e) {
            return $this->json(['message' => $e->getMessage()], 400);
        }
    }

    private function mapOnDto(ServerRequestInterface $request): CommentDto
    {
        $requestBody = $this->parseJson((string)$request->getBody());

        $dto = new CommentDto();
        $dto->topicId = (int)$request->getAttributes()['id'] ?: 0;
        $dto->parentId = $requestBody['parent_id'] ?? null;
        $dto->body = $requestBody['body'] ?? '';

        return $dto;
    }

    private function validateDto(CommentDto $dto): void
    {
        // check on exists topic by id

        if ($dto->parentId && !$this->commentRepository->exists($dto->parentId)) {
            throw new ValidationException('parent_id', 'Родительский комментарий не найден');
        }

        if (strlen($dto->body) < 3) {
            throw new ValidationException('body', 'Содержание комментария должно быть не менее чем 3 символа');
        }
    }
}
