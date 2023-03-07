<?php

declare(strict_types=1);

namespace App\API;

use App\Post\Application\Command\CreatePostCommand;
use App\Shared\Domain\Bus\CommandBus;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use App\Post\Domain\PostRepository;

// Feature #3
#[Route(path: '/posts/{post_id}', methods: ['GET'])]
class GetPostController
{
    public function __construct(
        private readonly PostRepository $repository
    ) {
    }

    public function __invoke($post_id, PostRepository $repository): Response
    {
        $uuid = Uuid::fromString($post_id);
        
        try{
            $result=$this->repository->find($uuid);
            if (!$result) {

                return new JsonResponse(
                    [
                        'error' => 'Posts does not exist',
                    ],
                    Response::HTTP_NOT_FOUND,
                );
            }
            $response_data = new Response(json_encode($result));
            $response_data->headers->set('Content-Type', 'application/json');
            $response_data->setStatusCode(200);
            return $response_data;
        } catch(Exception $exception){
            return new JsonResponse(
                [
                    'error' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }
        
    }
}
