<?php

namespace App\Controller;

use App\Entity\Films;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FilmController extends AbstractController
{
    /**
     * @Route("/createFilm", name="create_film")
     * @Route("/updateFilm/{id?1}", name="update_film")
     */
    public function index(Request $request, ManagerRegistry $doctrine, $id = null)
    {
        $entityManager = $doctrine->getManager();
        $isEditor = false;

        if (isset($id)) {
            $films = $entityManager->getRepository(Films::class)->find($id);
            if (!isset($films)) {
                return $this->redirectToRoute('listingFilm');
            }
            $isEditor = true;
        } else {
            $films = new Films;
        }

        $form = $this->createFormBuilder($films)
            ->add("title", TextType::class, [
                'required' => true,
            ])
            ->add("realisator", TextType::class)
            ->add("genre", TextType::class)
            ->add("save", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $films = $form->getData();

            $entityManager->persist($films);
            $entityManager->flush();

            return $this->redirectToRoute('listingFilm');
        }
        return $this->render('film/create.html.twig', [
            'form' => $form->createView(),
            'isEditor' => $isEditor
        ]);
    }


    /**
     * @Route("/listingFilm", name="listingFilm") 
     */
    public function listing(ManagerRegistry $doctrine)
    {
        $films = $doctrine->getManager()->getRepository(Films::class)->findAll();

        return $this->render("film/listing.html.twig", ["films" => $films]);
    }

    /**
     * @Route("/deleteFilm/{id}", name="delete_film") 
     */
    public function delete(ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();

        $film = $entityManager->getRepository(Films::class)->find($id);

        if (isset($film)) {
            $entityManager->remove($film);
            $entityManager->flush();
        }
        return $this->redirectToRoute("listingFilm");
    }
}
