<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return Paginator
     */
    public function findAllFilm(int $page, int $limit)
    {
        $query = $this->createQueryBuilder('f')
            ->select('f')
            ->orderBy('f.createdAt', 'DESC')
;
        $query->setFirstResult(($page-1) * $limit)
            ->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    /**
     * @param Request $request
     */
    public function search(Request $request)
    {
        $query = $this->createQueryBuilder('f')
            ->select('f');
        $titre = $request->request->get('titre');
        if(!empty($titre)) {
            $query->where('f.titre like :titre')
                ->setParameter('titre', '%' .$titre. '%');
        }
        $category = $request->request->get('category');
        if(!empty($category)) {
            $query->andWhere('f.categoryFilm = :categoryFilm')
                ->setParameter('categoryFilm', $category);
        }
        $year = $request->request->get('year');
        if(!empty($year)) {
            $query->andWhere('f.release_date = :year')
                ->setParameter('year', $year);
        }

        return $query->getQuery()->getResult();
    }
}
