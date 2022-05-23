<?php

namespace App\Controller;

use App\Entity\Excursion;
use App\Entity\Images;
use App\Form\ExcursionType;
use App\Repository\ExcursionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/excursion")
 */
class ExcursionController extends AbstractController
{
    /**
     * @Route("/", name="app_excursion_index", methods={"GET"})
     */
    public function index(ExcursionRepository $excursionRepository): Response
    {
        return $this->render('excursion/index.html.twig', [
            'excursions' => $excursionRepository->findAll(),
            
        ]);
    }

    /**
     * @Route("/new", name="app_excursion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ExcursionRepository $excursionRepository): Response
    {
        $excursion = new Excursion();
        $form = $this->createForm(ExcursionType::class, $excursion);
        $form->handleRequest($request);

    
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Images();
                $img->setUrl($fichier);
                $img->setName($fichier);
                $excursion->addImage($img);
            }
            $excursionRepository->add($excursion);
            return $this->redirectToRoute('app_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('excursion/new.html.twig', [
            'excursion' => $excursion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_excursion_show", methods={"GET"})
     */
    public function show(Excursion $excursion): Response
    {
        return $this->render('excursion/show.html.twig', [
            'excursion' => $excursion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_excursion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Excursion $excursion, ExcursionRepository $excursionRepository): Response
    {
        $form = $this->createForm(ExcursionType::class, $excursion);
   

        $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                // On récupère les images transmises
                $images = $form->get('images')->getData();
    
                // On boucle sur les images
                foreach ($images as $image) {
                    // On génère un nouveau nom de fichier
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();
    
                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );
    
                    // On crée l'image dans la base de données
                    $img = new Images();
                    $img->setUrl($fichier);
                    $img->setName($fichier);
                    $excursion->addImage($img);
                }
            $excursionRepository->add($excursion);
            return $this->redirectToRoute('app_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('excursion/edit.html.twig', [
            'excursion' => $excursion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_excursion_delete", methods={"POST"})
     */
    public function delete(Request $request, Excursion $excursion, ExcursionRepository $excursionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$excursion->getId(), $request->request->get('_token'))) {
            $excursionRepository->remove($excursion);
        }

        return $this->redirectToRoute('app_excursion_index', [], Response::HTTP_SEE_OTHER);
    }
}