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
            'articles'   => $articles,
            'page'      => $page,
            'nb_page'   => ceil(count($articles) / $nbParPage) ?: 1
        ));
    }

    public function menuAction($nombre)
    {
      $repository = $this->getDoctrine()->getManager()->getRepository('IsmBlogBundle:Article');

      $liste = $repository->findBy(
        array(),                 // Pas de critère
        array('date' => 'desc'), // On tri par date décroissante
        $nombre,                 // On sélectionne $nombre articles
        0                        // A partir du premier
      );

      return $this->render('IsmBlogBundle:Blog:menu.html.twig', array(
        'liste_articles' => $liste // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
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
