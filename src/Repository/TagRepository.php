<?php

namespace App\Repository;

use App\Entity\SchoolYear;
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

    /**
     * @return Tag[] Returns an array of Tag objects
     */
    public function findByNotNullDescription(): array
    {
        return $this->createQueryBuilder('t')
            ->Where('t.description IS NOT null')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByNullDescription(): array
    {
        return $this->createQueryBuilder('t')
            ->Where('t.description IS null')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
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
            ->getResult();
    }
    // Dans l'entité Tag

    /**
     * *this method finds tags based on a schoolyear, by making inner join with students and with tags.
     * @param SchoolYear The school year for which we want to find the tags
     * @return Tag[] Returns an array of Tag objects
     */
    public function findBySchoolYear(SchoolYear $schoolYear): array
    {

        // !Le query builder génère le code SQL suivant:
        // *SELECT tag.id, tag.name, tag.description
        // *FROM `tag` 
        // *INNER JOIN student_tag ON tag.id = student_tag.tag_id
        // *INNER JOIN student ON student.id =student_tag.student_id
        // *INNER JOIN school_year ON school_year.id =student.school_year_id
        // *WHERE school_year.id = 123
        // *GROUP BY tag tag.id, tag.name, tag.description
        // *ORDER BY tag.name

        return $this->createQueryBuilder('t')
            ->innerJoin('t.students', 'stud')
            ->innerJoin('stud.schoolYear', 'sy')
            ->Where('sy = :schoolYear')
            ->setParameter('schoolYear', $schoolYear)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Tag[] Returns an array of Tag objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
