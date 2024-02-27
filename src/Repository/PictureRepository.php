<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Picture>
 *
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    public function getLast(int $limit) :array
    {
       $query =$this ->createQueryBuilder('p')
                    ->orderBy('p.date', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery();
                    
                    return $query->getResult();

    }

    public function findAllWithoutEvent()
    {
        $query = $this ->creatQueryBuilder('p');

        $query->where($query->expr()->isNull('p.event'));
        return $query;
    }

 

    // public function paginations(int $limit, int $page)
    // {
    //     $query = $this ->createQueryBuilder('p')
    //     ->orderBy('p.date', 'DESC' )
    //     ->setMaxResults($limit)
    //     ->setFirstResult(($page -1 * $limit))
    //     ->getQuery();
                    
    //                 return $query->getResult();
    // }
//    /**
//     * @return Picture[] Returns an array of Picture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Picture
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
