<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BloggController extends AbstractController
{
    /**
     * @Route("/home",name="home_page")
     */
    public function indexAction():Response
    {
        return $this->render('blogg/index.html.twig');
    }
}
