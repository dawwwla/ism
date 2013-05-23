<?php

namespace Sdz\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sdz\SiteBundle\Form\ContactType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SdzSiteBundle:Default:index.html.twig');
    }

    public function contactAction()
    {
        $form = $this->createFormBuilder()
                ->add('email', 'email', array(
                      'label' => 'Adresse email',
                      'label_attr' => array('class' => 'control-label'),
                      ))
                ->add('subject', 'text', array(
                      'label' => 'Sujet',
                      'label_attr' => array('class' => 'control-label')
                      ))
                ->add('content', 'textarea', array(
                      'label' => 'Message',
                      'label_attr' => array('class' => 'control-label')
                      ))
                ->getForm();

        // On récupère la requête
        $request = $this->getRequest();

        // On vérifie qu'elle est de type POST
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            $form->bind($request);

            // On récupère les données
            $data = $form->getData();

            $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject($data['subject'])
                ->setFrom($data['email'])
                ->setTo('melkir13@gmail.com')
                ->setBody($data['content']);

            $this->get('mailer')->send($message);

            // Launch the message flash
            $this->get('session')->getFlashBag()->add('info', 'Message envoyé');
        }

        return $this->render('SdzSiteBundle:Default:contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function lienAction()
    {
        return $this->render('SdzSiteBundle:Default:lien.html.twig');
    }

    public function roadmapAction()
    {
        return $this->render('SdzSiteBundle:Default:roadmap.html.twig');
    }
}
