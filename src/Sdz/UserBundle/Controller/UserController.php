<?php

namespace Sdz\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Sdz\UserBundle\Entity\Membre;
use Sdz\UserBundle\Entity\User;
use Sdz\UserBundle\Form\MembreType;

class UserController extends Controller {

  public function indexAction()
  {
    $membre = $this->getUser()->getMembre();

    return $this->render('SdzUserBundle:Blog:index.html.twig', array('membre' => $membre));
  }

  public function ajouterAction()
  {
    // On créer l'entité User
    $user = $this->getUser();
    // On créer l'entité Membre
    $membre = new Membre;

    // On créer les champs du formulaire
    $form = $this->createForm(new MembreType, $membre);

    // On récupère la requête
    $request = $this->get('request');

    // On vérifie qu'elle est de type POST
    if ($request->getMethod() == 'POST') {
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $membre contient les valeurs entrées dans le formulaire par le visiteur
      $form->bind($request);

      if ($form->isValid()) {

        // On lie la fiche membre à l'utilisateur
        $user->setMembre($membre);

        // On l'enregistre notre objet $membre dans la base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', 'Fiche membre bien ajouté');

        // On redirige vers la page de visualisation du membre nouvellement créé
        return $this->redirect($this->generateUrl('sdzuser_fiche_voir', array('id' => $membre->getId())));
      }
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau

    return $this->render('SdzUserBundle:Blog:ajouter.html.twig', array(
      'user' => $user,
      'form' => $form->createView()
    ));
  }

  public function modifierAction(Membre $membre)
  {
    return $this->render('SdzUserBundle:Blog:modifier.html.twig');
  }

  public function supprimerAction(Membre $membre)
  {
    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'article contre cette faille
    $form = $this->createFormBuilder()->getForm();

    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid()) { // Ici, isValid ne vérifie donc que le CSRF
        // On supprime l'article
        $em = $this->getDoctrine()->getManager();
        $em->remove($membre);
        $em->flush();

        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', 'Fiche membre bien supprimé');

        // Puis on redirige vers l'accueil
        return $this->redirect($this->generateUrl('sdzuser_index'));
      }
    }

    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('SdzUserBundle:Blog:supprimer.html.twig', array(
      'membre' => $membre,
      'form'    => $form->createView()
    ));
  }

  public function voirAction(Membre $membre) {

    return $this->render('SdzUserBundle:Blog:voir.html.twig', array(
      'membre' => $membre
    ));
  }

}
