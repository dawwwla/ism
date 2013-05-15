<?php

namespace Sdz\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use JMS\SecurityExtraBundle\Annotation\Secure;

use Sdz\UserBundle\Entity\Membre;

class UserController extends Controller {

  public function ajouterMembreAction()
  {
    $membre = new Membre();
    $formBuilder = $this->createFormBuilder($membre);
    $formBuilder
      ->add('dateOfBirth',  'date')
      ->add('firstname',    'text')
      ->add('lastname',     'text')
      ->add('website',      'text')
      ->add('gender',       'text')
      ->add('phone',        'text')
      ->add('role',         'text')
      ->add('description',  'textarea');

    $form = $formBuilder->getForm();
    return $this->render('SdzUserBundle:Blog:ajouter.html.twig', array(
      'form' => $form->createView(),
    ));
  }

}
