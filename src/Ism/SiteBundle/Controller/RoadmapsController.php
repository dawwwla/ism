<?php

namespace Ism\SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ism\SiteBundle\Entity\Roadmaps;
use Ism\SiteBundle\Form\Type\RoadmapsType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Roadmaps controller.
 *
 */
class RoadmapsController extends Controller
{
    /**
     * Lists all Roadmaps entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IsmSiteBundle:Roadmaps')->findAll();

        return $this->render('IsmSiteBundle:Roadmaps:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Créer une nouvelle roadmap
     *
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function newAction()
    {
        $entity = new Roadmaps();
        $form = $this->createForm(new RoadmapsType(), $entity);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // On défini l'utilisateur connecté
                $entity->setUser($this->getUser());
                // On enregistre le roadmap en base de donnée
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Roadmap ajouté');
                return $this->redirect($this->generateUrl('roadmaps_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('IsmSiteBundle:Roadmaps:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Afficher les détails sur une roadmap
     *
     */
    public function showAction(Roadmaps $entity)
    {
        return $this->render('IsmSiteBundle:Roadmaps:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Modifier une roadmap
     *
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function editAction(Roadmaps $entity)
    {
        if (false === $this->isAuteurOrAdmin($entity)) {
            throw new \Exception('Vous ne disposer pas des droits suffisant pour modifier la roadmap.
                                 Vérifier que vous en êtes bien l\'auteur ou disposer des droits d\'administrateur.');
        }
        $form = $this->createForm(new RoadmapsType(), $entity);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Roadmap modifiée');
                return $this->redirect($this->generateUrl('roadmaps_show', array('id' => $entity->getId())));
            }
        }

        return $this->render('IsmSiteBundle:Roadmaps:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $form->createView(),
        ));
    }

    /**
     * Supprimer une roadmap
     *
     * @Secure(roles="ROLE_AUTEUR")
     */
    public function deleteAction(Roadmaps $entity)
    {
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'article contre cette faille
        $form = $this->createFormBuilder()->getForm();

        if (false === $this->isAuteurOrAdmin($entity)) {
            throw new \Exception('Vous ne disposer pas des droits suffisant pour supprimer la roadmap.
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
            $this->get('session')->getFlashBag()->add('success', 'Roadmap supprimée');
            return $this->redirect($this->generateUrl('ismsite_roadmaps'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('IsmSiteBundle:Roadmaps:delete.html.twig', array(
            'entity' => $entity,
            'delete_form' => $form->createView(),
        ));
    }

    /**
     * Vérifie si l'utilisateur courant est l'auteur de la roadmap ou alors s'il dispose des droits d'administrateur
     *
     * @param  Roadmaps             $entity Entité de la roadmaps
     *
     * @return boolean              Renvoi 'true' si c'est le cas, autrement 'false'
     */
    private function isAuteurOrAdmin(Roadmaps $entity)
    {
        $user = $this->get('security.context')->getToken()->getUser()->getId();
        $isAdmin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $auteur = $entity->getUser()->getId();
        // Si l'utilisateur courant est l'auteur de la roadmap ou dispose des droits d'administrateur on renvoi vrai
        if ($user == $auteur || $isAdmin) {
            return true;
        } else {
            return false;
        }
    }
}
