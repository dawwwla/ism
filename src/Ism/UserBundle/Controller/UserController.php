<?php

namespace Ism\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Ism\UserBundle\Entity\Membre;
use Ism\UserBundle\Entity\User;
use Ism\UserBundle\Form\MembreType;

class UserController extends Controller {

  public function indexAction()
  {
    $user = $this->getUser();

    return $this->render('IsmUserBundle:User:index.html.twig', array(
      'user' => $user
    ));
  }

  public function ajouterAction()
  {
    // On créer l'entité Membre
    $user = $this->getUser();

    if ($user->getMembre() != null) {
      throw new \Exception('Fiche membre déjà existante');
    }

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
 //       return $this->redirect($this->generateUrl('ismuser_index'));
        return $this->redirect($this->generateUrl('ismuser_voir', array('username' => $user->getUsername())));
      }
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau

    return $this->render('IsmUserBundle:User:ajouter.html.twig', array(
      'user' => $user,
      'form' => $form->createView()
    ));
  }

  public function modifierAction(Membre $membre)
  {
    $form = $this->createForm(new MembreType, $membre);
    $user = $this->getUser();

    $request = $this->getRequest();

    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid()) {
        // On enregistre le membre
        $em = $this->getDoctrine()->getManager();
        $em->persist($membre);
        $em->flush();
        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', 'Fiche membre bien modifié');

        return $this->redirect($this->generateUrl('ismuser_index'));
      }
    }

    return $this->render('IsmUserBundle:User:modifier.html.twig', array(
      'form'    => $form->createView(),
      'membre' => $membre,
      'user' => $user
    ));
  }

  public function supprimerAction(Membre $membre)
  {
    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'une fiche membre contre cette faille
    $form = $this->createFormBuilder()->getForm();

    $request = $this->getRequest();
    if ($request->getMethod() == 'POST') {
      $form->bind($request);

      if ($form->isValid()) { // Ici, isValid ne vérifie donc que le CSRF
        // On détache la fiche de l'utilisateur
        $user = $this->getUser()->setMembre(null);
        // On supprime la fiche membre
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->remove($membre);
        $em->flush();

        // On définit un message flash
        $this->get('session')->getFlashBag()->add('info', 'Fiche membre bien supprimé');

        // Puis on redirige vers l'accueil
        return $this->redirect($this->generateUrl('ismuser_index'));
      }
    }

    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('IsmUserBundle:User:supprimer.html.twig', array(
      'membre' => $membre,
      'form'    => $form->createView(),
      'user'    => $this->getUser()
    ));
  }

  public function voirAction(User $user) {

    return $this->render('IsmUserBundle:User:voir.html.twig', array(
      'user' => $user,
      'membre' => $user->getMembre()
    ));
  }

}
