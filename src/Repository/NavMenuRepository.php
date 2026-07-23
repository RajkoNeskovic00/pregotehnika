<?php

namespace App\Repository;

use App\Entity\NavMenu;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<NavMenu>
 *
 * @method NavMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method NavMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method NavMenu[]    findAll()
 * @method NavMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavMenu::class);
    }

    public function add(NavMenu $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NavMenu $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findMainMeni(): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.order_num', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
