<?php

namespace App\Controller;

use App\Repository\TrickRepository;
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
     * @param int                 $offset
     * @param int                 $limit
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    public function loadMoreTricks(
        TrickRepository $repository,
        SerializerInterface $serializer,
        int $offset = 0,
        int $limit = 4
    ): JsonResponse {
        $query = $repository->findBy([], ['createdAt' => 'DESC'], $limit, $offset);

        $data = $serializer->serialize($query, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);

        return new JsonResponse($data, 200, [], true);
    }
}
