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

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{
    /**
     * Creates a new Article entity.
     *
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

        $deleteForm = $this->createDeleteForm($article->getId());

        return $this->render('IsmBlogBundle:Article:show.html.twig', array(
            'article'      => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('IsmBlogBundle:Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $editForm = $this->createForm(new ArticleType(), $article);

        return $this->render('IsmBlogBundle:Article:edit.html.twig', array(
            'article'       => $article,
            'edit_form'     => $editForm->createView(),
        ));
    }

    /**
     * Edits an existing Article entity.
     *
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
     *
     */
    public function confirmAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('IsmBlogBundle:Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('IsmBlogBundle:Article:delete.html.twig' , array(
            'article'       => $article,
            'delete_form'   => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Article entity.
     *
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
}
