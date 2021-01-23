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
     * @Route("/tricks/load-more/{offset}/{limit}",
     *     name="load_more_tricks",
     *     options={"expose"=true},
     *     requirements={"offset"="\d+", "limit"="\d+"}
     * )
     * @param TrickRepository     $repository
     * @param SerializerInterface $serializer
     * @param int                 $offset
     * @param int                 $limit
     *
     * @return JsonResponse
     */
    public function loadMoreTricks(
        TrickRepository $repository,
        SerializerInterface $serializer,
        int $offset = 0,
        int $limit = 4
    ): JsonResponse {
        return $this->loadMoreResults($repository, $limit, $offset, $serializer);
    }

    /**
     * @Route("/comments/load-more/{offset}/{limit}",
     *     name="load_more_comments",
     *     options={"expose"=true},
     *     requirements={"offset"="\d+", "limit"="\d+"}
     * )
     * @param CommentRepository   $repository
     * @param SerializerInterface $serializer
     * @param int                 $offset
     * @param int                 $limit
     *
     * @return JsonResponse
     */
    public function loadMoreComments(
        CommentRepository $repository,
        SerializerInterface $serializer,
        int $offset = 0,
        int $limit = 4
    ): JsonResponse {
        return $this->loadMoreResults($repository, $limit, $offset, $serializer);
    }

    /**
     * @param ServiceEntityRepositoryInterface $repository
     * @param int                              $limit
     * @param int                              $offset
     * @param SerializerInterface              $serializer
     *
     * @return JsonResponse
     */
    private function loadMoreResults(ServiceEntityRepositoryInterface $repository, int $limit, int $offset, SerializerInterface $serializer): JsonResponse
    {
        $query = $repository->findBy([], ['createdAt' => 'DESC'], $limit, $offset);

        $data = $serializer->serialize($query, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);

        return new JsonResponse($data, 200, [], true);
    }
}
