<?php

namespace App\Repository;

use App\Entity\CourseAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseAccess>
 *
 * @method CourseAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseAccess[]    findAll()
 * @method CourseAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseAccess::class);
    }

    public function add(CourseAccess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CourseAccess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
