<?php

namespace Sdz\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SdzSiteBundle:Default:index.html.twig');
    }
}
