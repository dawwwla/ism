<?php

namespace Sdz\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SdzSiteBundle:Default:index.html.twig');
    }

    public function contactAction()
    {
        return $this->render('SdzSiteBundle:Default:contact.html.twig');
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
