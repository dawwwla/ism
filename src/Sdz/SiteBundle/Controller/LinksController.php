<?php

namespace Sdz\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sdz\SiteBundle\Entity\Links;
use Sdz\SiteBundle\Form\Type\LinksType;

/**
 * Links controller.
 *
 */
class LinksController extends Controller
{
    /**
     * Lists all Links entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SdzSiteBundle:Links')->findAll();

        return $this->render('SdzSiteBundle:Links:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Links entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Links();

        if ($this->getUser()) {
          // On définit le User par défaut dans le formulaire (utilisateur courant)
          $entity->setUser($this->getUser());
        }
        $form = $this->createForm(new LinksType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('links_show', array('id' => $entity->getId())));
        }

        return $this->render('SdzSiteBundle:Links:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Links entity.
     *
     */
    public function newAction()
    {
        $entity = new Links();
        $form = $this->createForm(new LinksType(), $entity);

        return $this->render('SdzSiteBundle:Links:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Links entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SdzSiteBundle:Links')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Links entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SdzSiteBundle:Links:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Links entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SdzSiteBundle:Links')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Links entity.');
        }

        $editForm = $this->createForm(new LinksType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SdzSiteBundle:Links:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Links entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SdzSiteBundle:Links')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Links entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new LinksType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('links_edit', array('id' => $id)));
        }

        return $this->render('SdzSiteBundle:Links:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Links entity.
     *
     */
    public function deleteAction(Links $entity)
    {
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille
        $form = $this->createFormBuilder()->getForm();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($entity);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('links'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('SdzSiteBundle:Links:delete.html.twig', array(
            'entity' => $entity,
            'delete_form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a Links entity by id.
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
