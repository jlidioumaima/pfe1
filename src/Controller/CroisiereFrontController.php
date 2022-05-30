<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CroisiereRepository;
use App\Repository\PaysRepository;
use App\Repository\ReservationRepository;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GrilleTarifaireRepository;
use App\Repository\ExcursionRepository;
use App\Repository\AgenceRepository;
use App\Entity\Client;
use App\Entity\GrilleTarifaire;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CroisiereFrontController extends AbstractController
{
    /**
     * @Route("/cruise", name="croisiere")
     */
    public function Croisiere(CroisiereRepository $croisiereRepository, PaysRepository $paysRepository): Response
    {
        $croisiere = $croisiereRepository->findAll();
        $pay = $paysRepository->findAll();
        return $this->render('Client/croisiere/index.html.twig', [
            'controller_name' => 'CroisiereFrontController',
            'croisiere' => $croisiere,
            'pays' => $pay,

        ]);
    }

    /**
     * @Route("/detailsCroisiere/{id}", name="app_detail_croisiere", methods={"GET"})
     */
    public function DetailVoyage(CroisiereRepository $croisiereRepository, string $id,PaysRepository $paysRepository,AgenceRepository $agenceRepository , GrilleTarifaireRepository $grilletarifaireRepository,ExcursionRepository $excursionRepository ): Response
    {
        $croisiere = $croisiereRepository->findByVoyagePay($id);
        $excursion = $excursionRepository->findByVoyagePay($id);
        $pay = $paysRepository->findAll();
        $agence = $agenceRepository->findAll();
        $grilletarifaires =  $grilletarifaireRepository->findByGrille($id);
        return $this->render('Client/croisiere/Detail.html.twig', [
            'controller_name' => 'HomeController',
            'croisiere' => $croisiere,
            'pays' => $pay,
            'excursion' => $excursion,
            'agence' => $agence,
            'grilletarifaire' => $grilletarifaires,


        ]);
    }
    /**
     * @Route("/packageDetailsCroisiere/{id}", name="app_package_Details_croisiere", methods={"GET", "POST"} )
     */
    public function PackageDetails(CroisiereRepository $croisiereRepository, Request $request, ReservationRepository $reservationRepository, SessionInterface $session, string $id, GrilleTarifaireRepository $grilletarifaireRepository, PaysRepository $paysRepository, ClientRepository $clientRepository,ExcursionRepository $excursionRepository): Response
    {
        $croisiere = $croisiereRepository->findById($id);
        $pay = $paysRepository->findAll();
        $excursion = $excursionRepository->findByVoyagePay($id);
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
             $croisiereSelectioner = $croisiereRepository->findOneBy(['id' => $id]);
                $clientRepository->add($client);
                $idclient = $clientRepository->find($client->getId());
                $client =  $clientRepository->find($idclient);
                $reservation = new Reservation();
                $reservation->setClient($client);
                $reservation->setGrilleTarifaire($grilletarifaire);       
                 $reservation->setStatus("non_traitee");
                $reservation->setOffre($croisiereSelectioner);
                $reservation->setDateCreation(new \DateTime('now'));
                $reservationRepository->add($reservation);
                return $this->redirectToRoute('app_confirmation_croisiere' ,['id'=>$id]);
            }

            return  $this->renderForm('Client/croisiere/reserver.html.twig', [
                'croisiere' => $croisiere,
                'adultes' => $adultes,
                'enfants' => $enfants,
                'grilletarifaire' => $grilletarifaire,
                'client' => $client,
                'form' => $form,
           
              
            ]);
        }

        return $this->render('Client/croisiere/PackageDetails.html.twig', [
            'croisiere' => $croisiere,
            'pays' => $pay,
            'grilletarifaire' => $grilletarifaires,
            'excursion' => $excursion,
            

        ]);
    }


 /**
     * @Route("/confirmation/{id}", name="app_confirmation_croisiere", methods={"GET"})
     */
    public function Confirmation(CroisiereRepository $croisiereRepository, ClientRepository $clientRepository,string $id, PaysRepository $paysRepository ,GrilleTarifaireRepository $grilletarifaireRepository): Response
    {
        $croisiere = $croisiereRepository->findByVoyagePay($id);
      
        $grilletarifaires =  $grilletarifaireRepository->findByGrille($id);
        return $this->render('Client/croisiere/confirmation.html.twig', [
            'controller_name' => 'HomeController',
            'croisiere' => $croisiere,
         
            'grilletarifaire' => $grilletarifaires,
          

        ]);
    }

    
}
