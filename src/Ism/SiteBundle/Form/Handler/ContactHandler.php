<?php
# src/Ism/SiteBundle/Form/Handler/ContactHandler.php

namespace Ism\SiteBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

use Ism\SiteBundle\Form\Model\ContactModel;

/**
 * The ContactHandler.
 * Use for manage your form submitions
 *
 * @author Thibault Vieux
 */
class ContactHandler
{

  protected $request;
  protected $form;
  protected $mailer;

  /**
   * Initialize the handler with the form and the request
   *
   * @param Form $form
   * @param Request $request
   * @param $mailer
   *
   */
  public function __construct(Form $form, Request $request, \Swift_Mailer $mailer)
  {
    $this->form = $form;
    $this->request = $request;
    $this->mailer = $mailer;
  }

  /**
   * Renvoi vrai si la requête de type POST et que le formulaire est valide
   * @return boolean Renvoi vrai si la requête de type POST et que le formulaire est valide
   */
  public function process()
  {
    // On vérifie que la requête est de type POST
    if ($this->request->getMethod() == 'POST') {
      // On fait le lien Requête <-> Formulaire
      $this->form->bind($this->request);
      // On vérifie que les champs du formulaire soient valide
      if ($this->form->isValid()) {
        $contact = $this->form->getData();
        $this->onSuccess($contact);
        return true;
      }
    }
    return false;
  }

  /**
   * Envoi un mail en cas de formulaire valide
   *
   * @param Contact $contact
   *
   */
  protected function onSuccess($contact)
  {
    //$destination = $this->container->getParameter('mailer_user');
    $message = \Swift_Message::newInstance()
        ->setSubject("ISM - Un utilisateur vous a envoyé un message")
        ->setFrom('no-reply@nobody.com')
        ->setTo('melkir13@gmail.com')
        ->setBody(
          sprintf("De : %s\n\nObjet : %s\n\n%s \n\n\nCeci est un message provenant du site d'ISM",
                   $contact->getEmail(), $contact->getSubject(), $contact->getContent()));

    $this->mailer->send($message);
  }

}
