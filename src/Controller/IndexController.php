<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrickRepository    $repository
     * @param Request            $request
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(TrickRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $page = max(1, $request->query->getInt('page', 1));

        $query = $repository->findBy([], ['createdAt' => 'DESC']);

        $tricksPaginated = $paginator->paginate(
            $query,
            $page,
            4
        );

        return $this->render('layout/index.html.twig', [
            'tricks' => $tricksPaginated,
        ]);
    }

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
    public function tricks(
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
