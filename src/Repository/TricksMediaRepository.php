<?php

namespace App\Repository;

use App\Entity\TricksMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|TricksMedia find($id, $lockMode = null, $lockVersion = null)
 * @method null|TricksMedia findOneBy(array $criteria, array $orderBy = null)
 * @method TricksMedia[]    findAll()
 * @method TricksMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TricksMedia::class);
    }
}
