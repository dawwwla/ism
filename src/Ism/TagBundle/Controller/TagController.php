<?php

namespace Ism\TagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ism\TagBundle\Entity\Tag;

class TagController extends Controller
{
    public function searchAction($name)
    {
      $tagRepo = $this->getDoctrine()->getManager()->getRepository('IsmTagBundle:Tag');
      // find all article ids matching a particular query
      $ids = $tagRepo->getResourceIdsForTag('ism_tag', $name);

      return $this->render('IsmTagBundle:Tag:index.html.twig', array(
        'name' => $name
      ));
    }
}
