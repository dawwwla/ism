<?php

namespace Ism\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('IsmCommentBundle:Default:index.html.twig', array('name' => $name));
    }
}
