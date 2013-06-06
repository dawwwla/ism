<?php

namespace Ism\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
  public function getArticles($nombreParPage, $page)
  {
    if ((int) $page < 1) {
      throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "'.$page.'").');
    }

    $query = $this->createQueryBuilder('a')
                  ->leftJoin('a.image', 'i')
                    ->addSelect('i')
                  ->leftJoin('a.categories', 'cat')
                    ->addSelect('cat')
                  ->where('a.publication = true')
                  ->orderBy('a.date', 'DESC')
                  ->getQuery();

    $query->setFirstResult(($page-1) * $nombreParPage)
          ->setMaxResults($nombreParPage);

    return new Paginator($query);
  }

  public function getArticlesForCategorie($name)
  {
    $query = $this->createQueryBuilder('a')
                  ->leftJoin('a.image', 'i')
                    ->addSelect('i')
                  ->leftJoin('a.categories', 'cat')
                    ->addSelect('cat')
                  ->where('a.publication = true')
                  ->andWhere('cat.nom LIKE :name')
                  ->orderBy('a.date', 'DESC')
                  ->getQuery();

    $query->setParameter('name', '%'.$name.'%');

    return new Paginator($query);
  }
}
