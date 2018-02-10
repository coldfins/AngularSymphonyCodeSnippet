<?php

namespace Mobntouch\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BackOfficeBundle:Default:index.html.twig', array('name' => $name));
    }
}
