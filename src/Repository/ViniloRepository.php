<?php

namespace App\Repository;

use App\Entity\Artista;
use App\Entity\Vinilo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vinilo>
 *
 * @method Vinilo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vinilo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vinilo[]    findAll()
 * @method Vinilo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViniloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vinilo::class);
    }

    public function save(Vinilo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vinilo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllPaginated($currentPage = 1):?Paginator
    {
        $query = $this->createQueryBuilder('1')
            ->orderBy('1.createdAt', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }

     # Paginate results.asd
    public function paginate($dql, $page = 1, $limit = 5):?Paginator
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return $paginator;
    }

    public function findAllByQuery(string $query): array
    {
        $qb = $this->createQueryBuilder('vinilo')
            ->where('vinilo.name LIKE :value')
            ->setParameter(':value', "%$query%");

        $query = $qb->getQuery();
        $vinils = $query->execute();

        #dump($vinils);

        return $vinils;
    }

//    /**
//     * @return Vinilo[] Returns an array of Vinilo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vinilo
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getFindAllQuery(): Query
    {
        return $this->createQueryBuilder('v')
            ->getQuery();
    }

    public function getFindByArtistQuery(Artista $artista): Query
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.artista = :artista')
            ->setParameter('artista', $artista)
            ->getQuery();
    }

    public function getFindByVinilNameQuery(string $text): Query
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.name LIKE :name')
            ->setParameter('name', '%'.$text.'%')
            ->getQuery();
    }

    public function getFindByCreationDate(string $dataInici, string $dataFi): Query
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.createdAt BETWEEN :dataInici AND :dataFi')
            ->setParameters(new ArrayCollection(array(
                new Parameter('dataInici', $dataInici),
                new Parameter('dataFi', $dataFi)
            )))
            ->getQuery();
    }
}
