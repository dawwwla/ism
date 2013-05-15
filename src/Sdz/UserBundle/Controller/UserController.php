<?php

namespace Sdz\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Sdz\UserBundle\Entity\Membre;
use Sdz\UserBundle\Form\UserType;

class UserController extends Controller {

  public function indexAction()
  {
    return $this->render('SdzUserBundle:Blog:index.html.twig');
  }

  public function ajouterAction()
  {
    $membre = new Membre();

    // On créer les champs du formulaire
    $form = $this->createForm(new UserType, $membre);

    // On récupère la requête
    $request = $this->get('request');

    // On vérifie qu'elle est de type POST
    if ($request->getMethod() == 'POST') {
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $membre contient les valeurs entrées dans le formulaire par le visiteur
      $form->bind($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {
        // On l'enregistre notre objet $membre dans la base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($membre);
        $em->flush();

        // On redirige vers la page de visualisation du membre nouvellement créé
        return $this->redirect($this->generateUrl('sdzuser_index', array('id' => $membre->getId())));
      }
    }

    // À ce stade :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire n'est pas valide, donc on l'affiche de nouveau

    return $this->render('SdzUserBundle:Blog:ajouter.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function voirAction() {
    return $this->redirect($this->generateUrl('sdzuser_fiche_view', array('id' => $membre->getId())));
  }

}