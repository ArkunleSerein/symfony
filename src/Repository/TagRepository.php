<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    //    /**
    //     * @return Tag[] Returns an array of Tag objects
    //     */
       public function findByNotNullDescription(): array
       {
           return $this->createQueryBuilder('t')
               ->Where('t.description IS NOT null')
               ->orderBy('t.name', 'ASC')
               ->getQuery()
               ->getResult()
           ;
       }

       public function findByNullDescription(): array
       {
           return $this->createQueryBuilder('t')
               ->Where('t.description IS null')
               ->orderBy('t.name', 'ASC')
               ->getQuery()
               ->getResult()
           ;
       }

       /**
        * *This method finds all tags containing a keyword anywhere in the tag name
        * @param string $keyword to search for
        * @return Tag[] Returns an array of Tag objects
        */
       
       public function findByKeyword(string $keyword): array
       {
        return $this->createQueryBuilder('t')
            ->where('t.name LIKE :keyword')
            ->orWhere('t.description LIKE :keyword')
            ->setParameter('keyword', "%$keyword%")
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
       }
    //    public function findOneBySomeField($value): ?Tag
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
