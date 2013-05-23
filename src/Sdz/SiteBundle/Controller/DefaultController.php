<?php

namespace Sdz\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sdz\SiteBundle\Form\ContactHandler;

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

        // Get the handler
        $formHandler = new ContactHandler($form, $request, $this->get('mailer'));

        // Le handler s'occupe de la gestion du formulaire
        $process = $formHandler->process();

        // Si la requête est post, on envoie le formulaire autrement on l'affiche
        if ($process)
        {
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
