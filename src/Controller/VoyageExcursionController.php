<?php

namespace App\Controller;
use App\Entity\Images;
use App\Entity\VoyageExcursion;
use App\Form\VoyageExcursionType;
use App\Repository\VoyageExcursionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/voyage/excursion")
 */
class VoyageExcursionController extends AbstractController
{
    /**
     * @Route("/", name="app_voyage_excursion_index", methods={"GET"})
     */
    public function index(VoyageExcursionRepository $voyageExcursionRepository): Response
    {
        return $this->render('voyage_excursion/index.html.twig', [
            'voyage_excursions' => $voyageExcursionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_voyage_excursion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, VoyageExcursionRepository $voyageExcursionRepository): Response
    {
        $voyageExcursion = new VoyageExcursion();
        $form = $this->createForm(VoyageExcursionType::class, $voyageExcursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
    
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $img->setUrl($fichier);
                $voyageExcursion->addImage($img);
            }
            $voyageExcursionRepository->add($voyageExcursion);
            return $this->redirectToRoute('app_voyage_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyage_excursion/new.html.twig', [
            'voyage_excursion' => $voyageExcursion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_voyage_excursion_show", methods={"GET"})
     */
    public function show(VoyageExcursion $voyageExcursion): Response
    {
        return $this->render('voyage_excursion/show.html.twig', [
            'voyage_excursion' => $voyageExcursion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_voyage_excursion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, VoyageExcursion $voyageExcursion, VoyageExcursionRepository $voyageExcursionRepository): Response
    {
        $form = $this->createForm(VoyageExcursionType::class, $voyageExcursion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
    
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $img->setUrl($fichier);
                $voyageExcursion->addImage($img);
            }
            $voyageExcursionRepository->add($voyageExcursion);
            return $this->redirectToRoute('app_voyage_excursion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voyage_excursion/edit.html.twig', [
            'voyage_excursion' => $voyageExcursion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_voyage_excursion_delete", methods={"POST"})
     */
    public function delete(Request $request, VoyageExcursion $voyageExcursion, VoyageExcursionRepository $voyageExcursionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyageExcursion->getId(), $request->request->get('_token'))) {
            $voyageExcursionRepository->remove($voyageExcursion);
        }

        return $this->redirectToRoute('app_voyage_excursion_index', [], Response::HTTP_SEE_OTHER);
    }
}
