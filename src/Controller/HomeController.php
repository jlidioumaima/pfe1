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
use App\Repository\GrilleTarifaireRepository;
use App\Repository\ReservationRepository;
use App\Entity\Reservation;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
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
     * @Route("/packageDetailsOffre/{id}", name="app_package_Details_offre" , methods={"GET", "POST"})
     */
    public function PackageDetailsOffre(OffresRepository $offreRepository, Request $request ,string $id,GrilleTarifaireRepository $grilletarifaireRepository, PaysRepository $paysRepository, ClientRepository $clientRepository): Response
    {
        $offre = $offreRepository->findById($id);
        $pay = $paysRepository->findAll();
        $grilletarifaire =  $grilletarifaireRepository->findByGrille($id);
    
        if ($request->isMethod('POST')) {
            $periode=$request->request->get("periode");
            $adultes=$request->request->get("adultes");
            $enfants=$request->request->get("enfants");
            $grilletarifaire =  $grilletarifaireRepository->find(intval($periode));

            $client = new Client();
            $form = $this->createForm(ClientType::class, $client);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
          
                $idclient=$request->request->get("client");
               
              
                $clientRepository->add($client);
            }
  
            return  $this->renderForm('Client/home/reserver.html.twig',[
                'offre' => $offre,
                'adultes' => $adultes,
                'enfants' => $enfants,
                'grilletarifaire' => $grilletarifaire,
                           'client' => $client,
                'form' => $form,
            ]);
        }
       
     return $this->render('Client/home/PackageDetails.html.twig', [
            'offre' => $offre,
            'pays' => $pay,
            'grilletarifaire' => $grilletarifaire,
           
        ]);
    }

    
    
    /**
     * @Route("/packageDetailsOmra/{id}", name="app_package_Details_omra" , methods={"GET", "POST"})
     */
    public function PackageDetailsOmra(OmraRepository $omraRepository,string $id,Request $request, ReservationRepository $reservationRepository, GrilleTarifaireRepository $grilletarifaireRepository, PaysRepository $paysRepository, ClientRepository $clientRepository): Response
    {
        $omra = $omraRepository->findById($id);
        $pay = $paysRepository->findAll();
        $grilletarifaires =  $grilletarifaireRepository->findByGrille($id);

        if ($request->isMethod('POST')) {
            $periode = $request->request->get("periode");
            $adultes = $request->request->get("adultes");
            $enfants = $request->request->get("enfants");
            $grilletarifaire =  $grilletarifaireRepository->find(intval($periode));
            $client = new Client();
            $form = $this->createForm(ClientType::class, $client);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
             $omraSelectioner = $omraRepository->findOneBy(['id' => $id]);
                $clientRepository->add($client);
                $idclient = $clientRepository->find($client->getId());
                $client =  $clientRepository->find($idclient);
                $reservation = new Reservation();
                $reservation->setClient($client);
                $reservation->setGrilleTarifaire($grilletarifaire);       
                 $reservation->setStatus("non_traitee");
                $reservation->setOffre($omraSelectioner);
                $reservation->setDateCreation(new \DateTime('now'));
                $reservationRepository->add($reservation);
                return $this->redirectToRoute('app_confirmation_voyageOrganiser' ,['id'=>$id]);
            }

            return  $this->renderForm('Client/omra/reserver.html.twig', [
                'omra' => $omra,
                'adultes' => $adultes,
                'enfants' => $enfants,
                'grilletarifaire' => $grilletarifaire,
                'client' => $client,
                'form' => $form,
              
            ]);
        }

        return $this->render('Client/omra/PackageDetails.html.twig', [
            'omra' => $omra,
            'pays' => $pay,
            'grilletarifaire' => $grilletarifaires,
        ]);
        }

/**
     * @Route("/detailAgence", name="app_detail_agence", methods={"GET"})
     */
    public function DetailAgence(AgenceRepository $AgenceRepository): Response
    {
        $agence = $AgenceRepository->findAll();
  
        return $this->render('Client/agence/Detail.html.twig', [
            'controller_name' => 'HomeController',
            'agence' => $agence,
           


        ]);
    }
    /**
     * @Route("/forum", name="app_forum", methods={"GET"})
     */
    public function Forum(): Response
    {
        
  
        return $this->render('Client/forum/forum.html.twig', [
            'controller_name' => 'HomeController',
            
           


        ]);
    }

      /**
     * @Route("/reserverOmra/{id}", name="app_reserver_omra", methods={"GET", "POST"})
     */
    public function ReserverOmra(OmraRepository $omraRepository,string $id, Request $request, ClientRepository $clientRepository): Response
    {
        $omra = $omraRepository->findById($id);
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->add($client);
        }
        return $this->renderForm('Client/omra/reserver.html.twig',[
      
            'omra' => $omra,
            'client' => $client,
            'form' => $form,

        ]);
    }

    
 /**
     * @Route("/confirmation/{id}", name="app_confirmation_voyageOrganiser", methods={"GET"})
     */
    public function Confirmation(voyageOrganiserRepository $voyageOrganiserRepository, ClientRepository $clientRepository,string $id, PaysRepository $paysRepository ,GrilleTarifaireRepository $grilletarifaireRepository): Response
    {
        $voyageOrganiser = $voyageOrganiserRepository->findByVoyagePay($id);
      
        $grilletarifaires =  $grilletarifaireRepository->findByGrille($id);
        return $this->render('Client/voyageOrganiser/confirmation.html.twig', [
            'controller_name' => 'HomeController',
            'voyageOrganiser' => $voyageOrganiser,
         
            'grilletarifaire' => $grilletarifaires,
          

        ]);
    }

    /**
     * @Route("/packageDetails/{id}", name="app_package_Details")
     */
    public function PackageDetails(VoyageOrganiserRepository $voyageOrganiserRepository ,Request $request, ReservationRepository $reservationRepository, string $id, GrilleTarifaireRepository $grilletarifaireRepository, PaysRepository $paysRepository, ClientRepository $clientRepository): Response    {
        $voyageOrganiser = $voyageOrganiserRepository->findById($id);
        $pay = $paysRepository->findAll();
        $grilletarifaires =  $grilletarifaireRepository->findByGrille($id);

        if ($request->isMethod('POST')) {
            $periode = $request->request->get("periode");
            $adultes = $request->request->get("adultes");
            $enfants = $request->request->get("enfants");
            $grilletarifaire =  $grilletarifaireRepository->find(intval($periode));
            $client = new Client();
            $form = $this->createForm(ClientType::class, $client);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
             $voyageOrganiserSelectioner = $voyageOrganiserRepository->findOneBy(['id' => $id]);
                $clientRepository->add($client);
                $idclient = $clientRepository->find($client->getId());
                $client =  $clientRepository->find($idclient);
                $reservation = new Reservation();
                $reservation->setClient($client);
                $reservation->setGrilleTarifaire($grilletarifaire);       
                 $reservation->setStatus("non_traitee");
                $reservation->setOffre($voyageOrganiserSelectioner);
                $reservation->setDateCreation(new \DateTime('now'));
                $reservationRepository->add($reservation);
                return $this->redirectToRoute('app_confirmation_voyageOrganiser' ,['id'=>$id]);
            }

            return  $this->renderForm('Client/destination/reserver.html.twig', [
                'voyageOrganiser' => $voyageOrganiser,
                'adultes' => $adultes,
                'enfants' => $enfants,
                'grilletarifaire' => $grilletarifaire,
                'client' => $client,
                'form' => $form,
              
            ]);
        }

        return $this->render('Client/destination/PackageDetails.html.twig', [
            'voyageOrganiser' => $voyageOrganiser,
            'pays' => $pay,
            'grilletarifaire' => $grilletarifaires,
        ]);
        }

    

     /**
     * @Route("/reserver/{id}", name="app_reserver", methods={"GET", "POST"})
     */
    public function Reserver(VoyageOrganiserRepository $VoyageOrganiserRepository,string $id, Request $request, ClientRepository $clientRepository): Response
    {
        $voyageOrganiser = $VoyageOrganiserRepository->findById($id);

        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->add($client);
        }
        return $this->renderForm('Client/destination/reserver.html.twig', [
      
            'voyageOrganiser' => $voyageOrganiser,
            'client' => $client,
            'form' => $form,

        ]);
    }
    
    /**
     * @Route("/omrafront", name="app_omra_front")
     */
    public function Omra(OmraRepository $omraRepository ,GrilleTarifaireRepository $grilletarifaireRepository): Response
    {
        $omra = $omraRepository->findAll();
        $grilletarifaire =  $grilletarifaireRepository->findAll();
        return $this->render('Client/Omra/index.html.twig', [
            'controller_name' => 'HomeController',
            'omra' => $omra,
            'grilletarifaire' => $grilletarifaire,



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