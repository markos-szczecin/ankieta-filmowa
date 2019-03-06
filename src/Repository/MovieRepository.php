<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @param int $movieId
     * @return string
     */
    public function getJsonById(int $movieId): string
    {
        /** @var Movie $movie */
        $movie = $this->createQueryBuilder('m')
            ->andWhere('m.id = :val')
            ->setParameter('val', $movieId)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
        $movie->getActors();
        return reset($this->createQueryBuilder('m')
            ->andWhere('m.id = :val')
            ->setParameter('val', $movieId)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        );
    }

    /**
     * @param array $notMoviesIds
     * @return Movie
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRandMovieForUser(array $notMoviesIds)
    {
        $builder = $this->createQueryBuilder('m');
        if ($notMoviesIds) {
            $builder
                ->andWhere('m.id NOT IN (:ids)')
                ->setParameter('ids', $notMoviesIds, Connection::PARAM_INT_ARRAY);
        }
        return $builder
            ->orderBy('m.users_marks_quantity', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param array $notMoviesIds
     * @param int $limit
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRandMoviesForUser(array $notMoviesIds, int $limit = 1)
    {
        if (1 >= $limit) {
            return [$this->getRandMovieForUser($notMoviesIds)];
        } else {
            $builder = $this->createQueryBuilder('m');
            if ($notMoviesIds) {
                $builder
                    ->andWhere('m.id NOT IN (:ids)')
                    ->setParameter('ids', $notMoviesIds, Connection::PARAM_STR_ARRAY);
            }

            return $builder
                ->orderBy('m.users_marks_quantity', 'ASC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }
    }



    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
