<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AjaxController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * AjaxController constructor.
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/tricks/load-more/{offset}/{limit}",
     *     name="load_more_tricks",
     *     options={"expose"=true},
     *     requirements={"offset"="\d+", "limit"="\d+"}
     * )
     * @param TrickRepository $repository
     * @param int             $offset
     * @param int             $limit
     *
     * @return JsonResponse
     */
    public function loadMoreTricks(
        TrickRepository $repository,
        int $offset = 0,
        int $limit = 4
    ): JsonResponse {
        return $this->loadMoreResults($repository, $limit, $offset, 'createdAt');
    }

    /**
     * @Route("/comments/load-more/{offset}/{limit}",
     *     name="load_more_comments",
     *     options={"expose"=true},
     *     requirements={"offset"="\d+", "limit"="\d+"}
     * )
     * @param CommentRepository $repository
     * @param int               $offset
     * @param int               $limit
     *
     * @return JsonResponse
     */
    public function loadMoreComments(
        CommentRepository $repository,
        int $offset = 0,
        int $limit = 4
    ): JsonResponse {
        return $this->loadMoreResults($repository, $limit, $offset, 'updatedAt');
    }

    /**
     * @param ServiceEntityRepositoryInterface $repository
     * @param int                              $limit
     * @param int                              $offset
     * @param string                           $param
     *
     * @return JsonResponse
     */
    private function loadMoreResults(
        ServiceEntityRepositoryInterface $repository,
        int $limit,
        int $offset,
        string $param
    ): JsonResponse {
        $query = $repository->findBy([], [$param => 'DESC'], $limit, $offset);

        $data = $this->serializer->serialize($query, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);

        return new JsonResponse($data, 200, [], true);
    }
}
