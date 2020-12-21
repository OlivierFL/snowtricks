<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @param int $offset
     *
     * @return int|mixed|string
     */
    public function findLastTricksPaginated(int $offset)
    {
        $query = $this->createQueryBuilder('t')
            ->addOrderBy('t.createdAt', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();

        return new Paginator($query);
    }
}
