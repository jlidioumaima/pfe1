<?php

namespace App\Controller;

use App\Form\SearchOffreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\OffresRepository;
use App\Repository\VoyageOrganiserRepository;
use App\Repository\PaysRepository;
use App\Repository\ExcursionRepository;
use App\Repository\RondonneeRepository;
use App\Repository\OmraRepository;

use App\Repository\AgenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(OffresRepository $OffresRepository, Request $request,  PaysRepository $paysRepository, AgenceRepository $agenceRepository): Response
    {
        $offres = $OffresRepository->findAll();
        $agence = $agenceRepository->findAll();
        $pay = $paysRepository->findAll();
        $form = $this->createForm(SearchOffreType::class);

        $search = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On recherche les annonces correspondant aux mots clés
            $offres = $OffresRepository->search(
                $search->get('mots')->getData(),
                $search->get('categorie')->getData(),
                $search->get('pays')->getData(),
                $search->get('grilleTarifaires')->getData()

            );
        }
        return $this->render('Client/home/index.html.twig', [
            'offres' => $offres,
            'agences' => $agence,
            'pays' => $pay,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/destination", name="app_destination")
     */
    public function Destination(VoyageOrganiserRepository $VoyageOrganiserRepository, PaysRepository $paysRepository): Response
    {
        $voyageOrganiser = $VoyageOrganiserRepository->findAll();
        $pay = $paysRepository->findAll();
        return $this->render('Client/destination/index.html.twig', [
            'controller_name' => 'HomeController',
            'voyageOrganiser' => $voyageOrganiser,
            'pays' => $pay,
        ]);
    }

    /**
     * @Route("/detailVoyage/{id}", name="app_detail", methods={"GET"})
     */
    public function DetailVoyage(VoyageOrganiserRepository $VoyageOrganiserRepository, string $id, PaysRepository $paysRepository): Response
    {
        $voyageOrganiser = $VoyageOrganiserRepository->findByVoyagePay($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/destination/Detail.html.twig', [
            'controller_name' => 'HomeController',
            'voyageOrganiser' => $voyageOrganiser,
            'pays' => $pay,


        ]);
    }
     /**
     * @Route("/packageDetailsOffre/{id}", name="app_package_Details_offre")
     */
    public function PackageDetailsOffre(OffresRepository $offreRepository,string $id, PaysRepository $paysRepository): Response
    {
        $offre = $offreRepository->findById($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/home/PackageDetails.html.twig', [
            'controller_name' => 'HomeController',
            'offre' => $offre,
            'pays' => $pay,

        ]);
    }
    
    /**
     * @Route("/packageDetailsOmra/{id}", name="app_package_Details_omra")
     */
    public function PackageDetailsOmra(OmraRepository $omraRepository,string $id, PaysRepository $paysRepository): Response
    {
        $omra = $omraRepository->findById($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/omra/PackageDetails.html.twig', [
            'controller_name' => 'HomeController',
            'omra' => $omra,
            'pays' => $pay,

        ]);
    }
      /**
     * @Route("/reserverOmra/{id}", name="app_reserver_omra")
     */
    public function ReserverOmra(OmraRepository $omraRepository,string $id): Response
    {
        $omra = $omraRepository->findById($id);

        return $this->render('Client/omra/reserver.html.twig', [
            'controller_name' => 'HomeController',
            'omra' => $omra,

        ]);
    }
    /**
     * @Route("/packageDetails/{id}", name="app_package_Details")
     */
    public function PackageDetails(VoyageOrganiserRepository $VoyageOrganiserRepository,string $id, PaysRepository $paysRepository): Response
    {
        $voyageOrganiser = $VoyageOrganiserRepository->findById($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/destination/PackageDetails.html.twig', [
            'controller_name' => 'HomeController',
            'voyageOrganiser' => $voyageOrganiser,
            'pays' => $pay,

        ]);
    }

     /**
     * @Route("/reserver/{id}", name="app_reserver")
     */
    public function Reserver(VoyageOrganiserRepository $VoyageOrganiserRepository,string $id): Response
    {
        $voyageOrganiser = $VoyageOrganiserRepository->findById($id);

        return $this->render('Client/destination/reserver.html.twig', [
            'controller_name' => 'HomeController',
            'voyageOrganiser' => $voyageOrganiser,

        ]);
    }
    /**
     * @Route("/omra", name="app_omra")
     */
    public function Omra(OmraRepository $omraRepository): Response
    {
        $omra = $omraRepository->findAll();
        return $this->render('Client/Omra/index.html.twig', [
            'controller_name' => 'HomeController',
            'omra' => $omra,



        ]);
    }
/**
     * @Route("/reserver/offre/{id}", name="app_reserver_offre")
     */
    public function ReserverOffre(OffresRepository $offreRepository ,string $id): Response
    {
        $offre = $offreRepository->findById($id);

        return $this->render('Client/home/reserver.html.twig', [
            'controller_name' => 'HomeController',
            'offre' => $offre,

        ]);
    }
    
   
}