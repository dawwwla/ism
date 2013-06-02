<?php

namespace Ism\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// Permet de définir les rôles requis pour effectuer les actions (supprimer, editer)
use JMS\SecurityExtraBundle\Annotation\Secure;

use Ism\BlogBundle\Entity\Article;
use Ism\BlogBundle\Entity\Membre;
use Ism\BlogBundle\Entity\Commentaire;

use Ism\BlogBundle\Form\ArticleType;
use Ism\BlogBundle\Form\ImageType;

use Ism\BlogBundle\Bigbrother\BigbrotherEvents;
use Ism\BlogBundle\Bigbrother\MessagePostEvent;

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{
    /**
     * Créer un article
     *
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function newAction()
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);

        $request = $this->getRequest();
        // Si la requête est de type POST on enregiste l'article autrement on affiche le formulaire
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $article->setUser($user = $this->getUser());
                // --- Début de notre fonctionnalité BigBrother ---
                // On crée l'évènement
                $event = new MessagePostEvent($article->getContenu(), $user);

                // On déclenche l'évènement
                $this->get('event_dispatcher')
                     ->dispatch(BigbrotherEvents::onMessagePost, $event);

                // On récupère ce qui a été modifié par le ou les listener(s), ici le message
                $article->setContenu($event->getMessage());
                // --- Fin de notre fonctionnalité BigBrother ---

                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Article bien ajouté');
                return $this->redirect($this->generateUrl('article_show', array('slug' => $article->getSlug())));
            }
        }

        return $this->render('IsmBlogBundle:Article:new.html.twig', array(
            'article' => $article,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Voir un article
     *
     */
    public function showAction(Article $article)
    {
        return $this->render('IsmBlogBundle:Article:show.html.twig', array(
            'article'      => $article,
        ));
    }

    /**
     * Modifier un article
     *
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function editAction(Article $article)
    {
        if (false === $this->isAuteurOrAdmin($article)) {
            throw new \Exception('Vous ne disposer pas des droits suffisant pour modifier l\'article.
                                 Vérifier que vous en êtes bien l\'auteur ou disposer des droits d\'administrateur.');
        }

        $form = $this->createForm(new ArticleType(), $article);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Article bien modifié');
                return $this->redirect($this->generateUrl('article_show', array('slug' => $article->getSlug())));
            }
        }

        return $this->render('IsmBlogBundle:Article:edit.html.twig', array(
            'article'       => $article,
            'edit_form'     => $form->createView(),
        ));
    }

    /**
     * Supprimer un article
     *
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function deleteAction(Article $article)
    {
        if (false === $this->isAuteurOrAdmin($article)) {
            throw new \Exception('Vous ne disposer pas des droits suffisant pour supprimer l\'article.
                                 Vérifier que vous en êtes bien l\'auteur ou disposer des droits d\'administrateur.');
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille
        $form = $this->createFormBuilder()->getForm();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($article);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Article bien supprimé');
                return $this->redirect($this->generateUrl('ismblog'));
            }
        }

        return $this->render('IsmBlogBundle:Article:delete.html.twig' , array(
            'article'       => $article,
            'delete_form'   => $form->createView(),
        ));
    }

    /**
     * Vérifie si l'utilisateur courant est l'auteur de l'article ou alors s'il dispose des droits d'administrateur
     *
     * @param  Article  $article Entité de l'article
     *
     * @return boolean          Renvoi 'true' si c'est le cas, autrement 'false'
     */
    private function isAuteurOrAdmin(Article $article)
    {
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $isAdmin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $auteur = $article->getUser()->getId();
        // Si l'utilisateur courant est l'auteur de l'article ou dispose des droits d'administrateur on renvoi vrai
        if ($user == $auteur || $isAdmin) {
            return true;
        } else {
            return false;
        }
    }
}
