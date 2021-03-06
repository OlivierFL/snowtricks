<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
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
     *     options={"expose": true},
     *     requirements={"offset": "\d+", "limit": "\d+"}
     * )
     *
     * @param TrickRepository $repository
     * @param int             $offset
     * @param int             $limit
     *
     * @throws ExceptionInterface
     *
     * @return JsonResponse
     */
    public function loadMoreTricks(
        TrickRepository $repository,
        int $offset = 0,
        int $limit = 4
    ): JsonResponse {
        return $this->loadMoreResults($repository, $limit, $offset, 'createdAt', [], true);
    }

    /**
     * @Route("{id}/comments/load-more/{offset}/{limit}",
     *     name="load_more_comments",
     *     options={"expose": true},
     *     requirements={"offset": "\d+", "limit": "\d+", "id": "\d+"}
     * )
     *
     * @param CommentRepository $repository
     * @param int               $offset
     * @param int               $limit
     * @param int               $id
     *
     * @throws ExceptionInterface
     *
     * @return JsonResponse
     */
    public function loadMoreComments(
        CommentRepository $repository,
        int $id,
        int $offset = 0,
        int $limit = 4
    ): JsonResponse {
        return $this->loadMoreResults($repository, $limit, $offset, 'updatedAt', [
            'isValid' => true,
            'trick' => $id,
        ]);
    }

    /**
     * @param ServiceEntityRepositoryInterface $repository
     * @param int                              $limit
     * @param int                              $offset
     * @param string                           $criteria
     * @param null|array                       $options
     * @param bool                             $tricks
     *
     * @throws ExceptionInterface
     *
     * @return JsonResponse
     */
    private function loadMoreResults(
        ServiceEntityRepositoryInterface $repository,
        int $limit,
        int $offset,
        string $criteria,
        array $options = null,
        bool $tricks = false
    ): JsonResponse {
        $query = $repository->findBy($options ?? [], [$criteria => 'DESC'], $limit, $offset);

        $data = $this->serializer->normalize($query, null, [
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);

        if ($tricks) {
            if (null !== $this->getUser()) {
                $user = $this->serializer->normalize($this->getUser(), null, [
                    AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    },
                ]);
            }
            $data = $this->serializer->serialize(
                [
                    'tricks' => $data,
                    'user' => $user ?? null,
                ],
                'json'
            );
        } else {
            $data = $this->serializer->serialize($data, 'json');
        }

        return new JsonResponse($data, 200, [], true);
    }
}
