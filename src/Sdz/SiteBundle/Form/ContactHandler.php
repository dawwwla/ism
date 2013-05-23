<?php
# src/Sdz/SiteBundle/Form/ContactHandler.php

namespace Sdz\SiteBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

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
      // On récupère les données
      $data = $this->form->getData();

      $this->onSuccess($data);
      return true;
    }
    return false;
  }

  protected function onSuccess($data)
  {
    $message = \Swift_Message::newInstance()
        ->setContentType('text/html')
        ->setSubject($data['subject'])
        ->setFrom($data['email'])
        ->setTo('melkir13@gmail.com')
        ->setBody($data['content']);

    $this->mailer->send($message);
  }

}
