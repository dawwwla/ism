<?php

namespace Ism\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ism\SiteBundle\Entity\Links;
use Ism\SiteBundle\Form\Type\LinksType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Links controller.
 *
 */
class LinksController extends Controller
{
    /**
     * Liste tous les liens
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        // On récupére tous les liens
        $entities = $em->getRepository('IsmSiteBundle:Links')->findAll();

        return $this->render('IsmSiteBundle:Links:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Créer un nouveau lien
     *
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function newAction()
    {
        $entity = new Links();
        $form = $this->createForm(new LinksType(), $entity);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On défini l'utilisateur connecté
                $entity->setUser($this->getUser());
                // On enregistre le lien en base de donnée
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Lien ajouté');
                return $this->redirect($this->generateUrl('links_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('IsmSiteBundle:Links:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Afficher les détails sur un lien
     *
     */
    public function showAction(Links $entity)
    {
        return $this->render('IsmSiteBundle:Links:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Modifier un lien
     *
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function editAction(Links $entity)
    {
        if (false === $this->isAuteurOrAdmin($entity)) {
            throw new \Exception('Vous ne disposer pas des droits suffisant pour modifier le lien.
                                 Vérifier que vous en êtes bien l\'auteur ou disposer des droits d\'administrateur.');
        }
        $form = $this->createForm(new LinksType(), $entity);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Lien modifié');
                return $this->redirect($this->generateUrl('links_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('IsmSiteBundle:Links:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $form->createView(),
        ));
    }

    /**
     * Supprimer un lien
     *
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function deleteAction(Links $entity)
    {
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille
        $form = $this->createFormBuilder()->getForm();

        if (false === $this->isAuteurOrAdmin($entity)) {
            throw new \Exception('Vous ne disposer pas des droits suffisant pour supprimer le lien.
                                 Vérifier que vous en êtes bien l\'auteur ou disposer des droits d\'administrateur.');
        }

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($entity);
                $em->flush();
            }
            $this->get('session')->getFlashBag()->add('success', 'Lien supprimé');
            return $this->redirect($this->generateUrl('ismsite_links'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('IsmSiteBundle:Links:delete.html.twig', array(
            'entity' => $entity,
            'delete_form' => $form->createView(),
        ));
    }

    /**
     * Vérifie si l'utilisateur courant est l'auteur du lien ou alors s'il dispose des droits d'administrateur
     *
     * @param  Lien  $entity    Entité du lien
     *
     * @return boolean          Renvoi 'true' si c'est le cas, autrement 'false'
     */
    private function isAuteurOrAdmin(Links $entity)
    {
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $isAdmin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $auteur = $entity->getUser()->getId();
        // Si l'utilisateur courant est l'auteur du lien ou dispose des droits d'administrateur on renvoi vrai
        if ($user == $auteur || $isAdmin) {
            return true;
        } else {
            return false;
        }
    }
}
