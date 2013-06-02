<?php

namespace Ism\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// Permet de définir les rôles requis pour effectuer les actions (supprimer, editer)
use JMS\SecurityExtraBundle\Annotation\Secure;

use Ism\BlogBundle\Entity\Article;
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
     * Creates a new Article entity.
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function createAction(Request $request)
    {
        $article  = new Article();

        if ($user = $this->getUser()) {
            // On définit le User par défaut dans le formulaire (utilisateur courant)
            $article->setUser($user);
        }

        $form = $this->createForm(new ArticleType(), $article);
        $form->bind($request);

        if ($form->isValid()) {
            // --- Début de notre fonctionnalité BigBrother ---
            // On crée l'évènement
            $event = new MessagePostEvent($article->getContenu(), $this->getUser());

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

        return $this->render('IsmBlogBundle:Article:new.html.twig', array(
            'article' => $article,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Article entity.
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function newAction()
    {
        $article = new Article();

        $form   = $this->createForm(new ArticleType(), $article);

        return $this->render('IsmBlogBundle:Article:new.html.twig', array(
            'article' => $article,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Article entity.
     *
     */
    public function showAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$article) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        return $this->render('IsmBlogBundle:Article:show.html.twig', array(
            'article'      => $article,
        ));
    }

    /**
     * Displays a form to edit an existing Article entity.
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('IsmBlogBundle:Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        if (false === $this->isAuteurOrAdmin($article)) {
            throw new \Exception('Vous ne disposer pas des droits suffisant pour modifier l\'article.
                                 Vérifier que vous en êtes bien l\'auteur ou disposer des droits d\'administrateur.');
        }

        $editForm = $this->createForm(new ArticleType(), $article);

        return $this->render('IsmBlogBundle:Article:edit.html.twig', array(
            'article'       => $article,
            'edit_form'     => $editForm->createView(),
        ));
    }

    /**
     * Edits an existing Article entity.
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('IsmBlogBundle:Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $editForm = $this->createForm(new ArticleType(), $article);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($article);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Article bien modifié');
            return $this->redirect($this->generateUrl('article_show', array('slug' => $article->getSlug())));
        }

        return $this->render('IsmBlogBundle:Article:edit.html.twig', array(
            'article'       => $article,
            'edit_form'     => $editForm->createView(),
        ));
    }

    /**
     * Affiche un message de confirmation pour la suppression.
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function confirmAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('IsmBlogBundle:Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        if (false === $this->isAuteurOrAdmin($article)) {
            throw new \Exception('Vous ne disposer pas des droits suffisant pour supprimer l\'article.
                                 Vérifier que vous en êtes bien l\'auteur ou disposer des droits d\'administrateur.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsmBlogBundle:Article:delete.html.twig' , array(
            'article'       => $article,
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Article entity.
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository('IsmBlogBundle:Article')->find($id);

            if (!$article) {
                throw $this->createNotFoundException('Unable to find Article entity.');
            }

            $em->remove($article);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Article bien supprimé');
        }

        return $this->redirect($this->generateUrl('ismblog'));
    }

    /**
     * Creates a form to delete a Article entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
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
