<?php

namespace Ism\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    /**
     * Affiche les articles
     * @param  int $page  Page du blog
     * @return articles    Articles de la page
     *         page       Numéro de la page
     *         nb_pages   Nombre de page totale
     */
    public function indexAction($page)
    {
        // On récupère le nombre d'article par page depuis un paramètre du conteneur
        // cf app/config/parameters.yml
        $nbParPage = $this->container->getParameter('ismblog.nombre_par_page');

        // On récupère les articles de la page courante
        $articles = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('IsmBlogBundle:Article')
                         ->getArticles($nbParPage, $page); // getArticles() est défini dans ArticleRepository

        // On passe le tout à la vue
        return $this->render('IsmBlogBundle:Blog:index.html.twig', array(
            'articles'  => $articles,
            'page'      => $page,
            'nb_page'   => ceil(count($articles) / $nbParPage) ?: 1
        ));
    }

    public function menuAction($nombre)
    {
      $em = $this->getDoctrine()->getManager();
      // On affiche récupére les derniers articles
      $repository = $em->getRepository('IsmBlogBundle:Article');
      $articles = $repository->findBy(
        array(),                 // Pas de critère
        array('date' => 'desc'), // On tri par date décroissante
        $nombre,                 // On sélectionne $nombre articles
        0                        // A partir du premier
      );
      // On récupére les mot-clés
      $tagRepo = $em->getRepository('IsmTagBundle:Tag');
      $tags = $tagRepo->getTagsWithCountArray('ism_tag');
      // On récupére les catégories
      $repository = $em->getRepository('IsmBlogBundle:Categorie');
      $categories = $repository->findAll();

      return $this->render('IsmBlogBundle:Blog:menu.html.twig', array(
        'liste_articles'    => $articles, // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
        'liste_tags'        => $tags,
        'liste_categories'  => $categories
      ));
    }

    public function searchCategorieAction($name)
    {
      $articles = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('IsmBlogBundle:Article')
                       ->getArticlesForCategorie($name);

      // On passe le tout à la vue
      return $this->render('IsmBlogBundle:Search:categorie.html.twig', array(
          'articles'  => $articles,
          'name'      => $name
      ));
    }

    public function searchTagAction($name)
    {
      $em = $this->getDoctrine()->getManager();
      // find all article ids matching a particular query
      $ids = $em->getRepository('IsmTagBundle:Tag')->getResourceIdsForTag('ism_tag', $name);

      if (null != $ids) {
        $query = 'SELECT a FROM IsmBlogBundle:Article a Where a.id IN (:ids)';
        $articles = $em->createQuery($query)->setParameter('ids', $ids)->getResult();
      } else {
        $articles = null;
      }

      return $this->render('IsmBlogBundle:Search:tag.html.twig', array(
        'name'      => $name,
        'articles'  => $articles
      ));
    }

    public function feedAction()
    {
      $articles = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('IsmBlogBundle:Article')
                       ->getArticles(10, 1);

      $lastArticle = current($articles->getIterator());

      return $this->render('IsmBlogBundle:Blog:feed.xml.twig', array(
        'articles'  => $articles,
        'buildDate' => $lastArticle->getDate()
      ));
    }
}
