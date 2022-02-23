<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Films;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    /**
     * @Route("/createFilm", name="create_film")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
		
        $entityManager = $doctrine->getManager();

        $film = new Films;
        $film->setTitle('Les dents de la mer');
        $film->setRealisator("Steven Spielberg");
        $film->setGenre('horreur');

        $entityManager->persist($film);
        $entityManager->flush();

        return new Response('Un nouveau film a été créé : ' . $film->getTitle());
    }


    /**
     * @Route("/listingFilm", name="listingFilm") 
     */
    public function listing(ManagerRegistry $doctrine)
    {
        $films = $doctrine->getManager()->getRepository(Film::class)->findAll();

        return $this->render("navigation/films.html.twig", ["films" => $films]);
    }
}
