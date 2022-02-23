<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\isEmpty;

class NavController extends AbstractController
{
    /**
     * @Route("/", name="accueil") 
     */
    public function accueil()
    {
        return $this->render("navigation/accueil.html.twig");
    }

    /**
     * @Route("/redirect", name="redirect") 
     */
    public function homeRedirect()
    {
        return $this->redirectToRoute("accueil");
    }
}
