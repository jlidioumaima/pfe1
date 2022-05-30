<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RondonneeRepository;
use App\Repository\GrilleTarifaireRepository;
use App\Repository\PaysRepository;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ReservationRepository;
use App\Entity\Reservation;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
class RandonneeFrontController extends AbstractController
{
    /**
     * @Route("/randonnee/front", name="app_randonnee_front")
     */
   
    public function randonnee(RondonneeRepository $randonneeRepository,PaysRepository $paysRepository): Response
    {
        $rondonnee = $randonneeRepository->findAll();
        $pay = $paysRepository->findAll();
        return $this->render('Client/Randonnee/index.html.twig', [
            'controller_name' => 'RandonneeFrontController',
            'rondonnee' => $rondonnee,
            'pays' => $pay,

        ]);
    }
    /**
     * @Route("/detailsrandonnee/{id}", name="app_detail_randonnee", methods={"GET"})
     */
    public function DetailVoyage(RondonneeRepository $randonneeRepository, string $id, PaysRepository $paysRepository): Response
    {
        $rondonnee = $randonneeRepository->findByVoyagePay($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/Randonnee/Detail.html.twig', [
            'controller_name' => 'RandonneeFrontController',
            'rondonnee' => $rondonnee,
            'pays' => $pay,


        ]);
    }
    /**
     * @Route("/packageDetailsrandonnee/{id}", name="app_package_Details_randonnee",  methods={"GET", "POST"})
     */
    public function PackageDetails(RondonneeRepository $randonneeRepository, Request $request, ReservationRepository $reservationRepository, string $id, GrilleTarifaireRepository $grilletarifaireRepository, PaysRepository $paysRepository, ClientRepository $clientRepository): Response
    {
        $rondonnee = $randonneeRepository->findById($id);
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
             $rondonneeSelectioner = $randonneeRepository->findOneBy(['id' => $id]);
                $clientRepository->add($client);
                $idclient = $clientRepository->find($client->getId());
                $client =  $clientRepository->find($idclient);
                $reservation = new Reservation();
                $reservation->setClient($client);
                $reservation->setGrilleTarifaire($grilletarifaire);       
                 $reservation->setStatus("non_traitee");
                $reservation->setOffre($rondonneeSelectioner);
                $reservation->setDateCreation(new \DateTime('now'));
                $reservationRepository->add($reservation);
                return $this->redirectToRoute('app_confirmation_rondonnee' ,['id'=>$id]);
            }

            return  $this->renderForm('Client/Randonnee/reserver.html.twig', [
                'rondonnee' => $rondonnee,
                'adultes' => $adultes,
                'enfants' => $enfants,
                'grilletarifaire' => $grilletarifaire,
                'client' => $client,
                'form' => $form,
              
            ]);
        }

        return $this->render('Client/Randonnee/PackageDetails.html.twig', [
            'rondonnee' => $rondonnee,
            'pays' => $pay,
            'grilletarifaire' => $grilletarifaires,
            

        ]);
    }


    
 /**
     * @Route("/confirmation/{id}", name="app_confirmation_rondonnee", methods={"GET"})
     */
    public function Confirmation(RondonneeRepository $randonneeRepository, ClientRepository $clientRepository,string $id, PaysRepository $paysRepository ,GrilleTarifaireRepository $grilletarifaireRepository): Response
    {
        $rondonnee = $randonneeRepository->findByVoyagePay($id);
      
        $grilletarifaires =  $grilletarifaireRepository->findByGrille($id);
        return $this->render('Client/Randonnee/confirmation.html.twig', [
            'controller_name' => 'HomeController',
            'rondonnee' => $rondonnee,
         
            'grilletarifaire' => $grilletarifaires,
          

        ]);
    }
     
}