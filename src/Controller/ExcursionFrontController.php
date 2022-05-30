<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ExcursionRepository;
use App\Repository\PaysRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\GrilleTarifaireRepository;
class ExcursionFrontController extends AbstractController
{
    /**
     * @Route("/excursionfront", name="app_excursion_front")
     */
   
    public function excursion(ExcursionRepository $excursionRepository,PaysRepository $paysRepository): Response
    {
        $excursion = $excursionRepository->findAll();
        $pay = $paysRepository->findAll();
        return $this->render('Client/excursion/index.html.twig', [
            'controller_name' => 'ExcursionFrontController',
            'excursion' => $excursion,
            'pays' => $pay,

        ]);
    }
     /**
     * @Route("/detailsexcursion/{id}", name="app_detail_excursion", methods={"GET"})
     */
    public function DetailVoyage(ExcursionRepository $excursionRepository, string $id, PaysRepository $paysRepository): Response
    {
        $excursion = $excursionRepository->findByVoyagePay($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/excursion/Detail.html.twig', [
            'controller_name' => 'HomeController',
            'excursion' => $excursion,
            'pays' => $pay,


        ]);
    }
    /**
     * @Route("/packageDetailsexcursion/{id}", name="app_package_Details_excursion", methods={"GET", "POST"})
     */
    
    public function PackageDetails(ExcursionRepository $excursionRepository, Request $request,string $id,GrilleTarifaireRepository $grilletarifaireRepository, PaysRepository $paysRepository, ClientRepository $clientRepository): Response
    {
        $excursion = $excursionRepository->findById($id);
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
  
            return  $this->renderForm('Client/excursion/reserver.html.twig',[
                'excursion' => $excursion,
                'adultes' => $adultes,
                'enfants' => $enfants,
                'grilletarifaire' => $grilletarifaire,
                           'client' => $client,
                'form' => $form,
            ]);
        }
       
     return $this->render('Client/excursion/PackageDetails.html.twig', [
            'excursion' => $excursion,
            'pays' => $pay,
            'grilletarifaire' => $grilletarifaire,
           
        ]);
    }

     /**
     * @Route("/reserver/excursion/{id}", name="app_reserver_excursion", methods={"GET", "POST"})
     */
    public function Reserver(ExcursionRepository $excursionRepository ,string $id , Request $request, ClientRepository $clientRepository): Response
    {
        $excursion = $excursionRepository->findById($id);
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->add($client);
        }
        return $this->renderForm('Client/excursion/reserver.html.twig', [
      
            'excursion' => $excursion,
            'client' => $client,
            'form' => $form,

        ]);
    }
}
