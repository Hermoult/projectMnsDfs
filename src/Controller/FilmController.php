<?php

namespace App\Controller;

use DateTime;
use App\Entity\Film;
use App\Form\FilmType;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilmController extends AbstractController
{
    /**
     * @Route("/createFilm", name="create_film")
     * @Route("/updateFilm/{id}", name="update_film")
     */
    public function index(Film $film = null, Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator)
    {
        $entityManager = $doctrine->getManager();

        if (!$film) {
            $film = new Film;
        }


        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$film->getId()) {
                $film->setCreatedAt(new DateTimeImmutable());
            }
            $film->setUpdatedAt(new DateTime());

            $film = $form->getData();
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('listing');
        }

        $errors = $validator->validate($film);

        return $this->render('film/form.html.twig', [
            'form' => $form->createView(),
            'isEditor' => $film->getId(),
            'error' => $errors
        ]);
    }


    /**
     * @Route("/", name="listing") 
     */
    public function listing(ManagerRegistry $doctrine)
    {
        $films = $doctrine->getManager()->getRepository(Film::class)->findAll();
        return $this->render("film/listing.html.twig", ["films" => $films]);
    }

    /**
     * @Route("/deleteFilm/{id}", name="delete_film") 
     */
    public function delete(ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        if (isset($film)) {
            $entityManager->remove($film);
            $entityManager->flush();
        }
        return $this->redirectToRoute("listing");
    }
}
