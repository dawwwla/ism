<?php
# src/Sdz/SiteBundle/Form/Handler/ContactHandler.php

namespace Sdz\SiteBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

use Sdz\SiteBundle\Form\Model\Contact;

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
  public function __construct(Form $form, Request $request, $mailer)
  {
    $this->form = $form;
    $this->request = $request;
    $this->mailer = $mailer;
  }

  public function process()
  {
    // On vérifie qu'elle est de type POST
    if ($this->request->getMethod() == 'POST') {
      // On fait le lien Requête <-> Formulaire
      $this->form->bind($this->request);

      if ($this->form->isValid()) {
        $contact = $this->form->getData();
        $this->onSuccess($contact);
        return true;
      }
    }
    return false;
  }

  /**
   * Send mail on success
   *
   * @param Contact $contact
   *
   */
  protected function onSuccess($contact)
  {
    $message = \Swift_Message::newInstance()
        ->setContentType('text/html')
        ->setSubject($contact->getSubject())
        ->setFrom($contact->getEmail())
        ->setTo('melkir13@gmail.com')
        ->setBody($contact->getContent());

    $this->mailer->send($message);
  }

}
