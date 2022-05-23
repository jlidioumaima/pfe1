<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CroisiereRepository;
use App\Repository\PaysRepository;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;

class CroisiereFrontController extends AbstractController
{
    /**
     * @Route("/cruise", name="croisiere")
     */
    public function Croisiere(CroisiereRepository $croisiereRepository,PaysRepository $paysRepository): Response
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
    public function DetailVoyage(CroisiereRepository $croisiereRepository, string $id, PaysRepository $paysRepository): Response
    {
        $croisiere = $croisiereRepository->findByVoyagePay($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/croisiere/Detail.html.twig', [
            'controller_name' => 'HomeController',
            'croisiere' => $croisiere,
            'pays' => $pay,


        ]);
    }
    /**
     * @Route("/packageDetailsCroisiere/{id}", name="app_package_Details_croisiere")
     */
    public function PackageDetails(CroisiereRepository $croisiereRepository,string $id, PaysRepository $paysRepository): Response
    {
        $croisiere = $croisiereRepository->findById($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/croisiere/PackageDetails.html.twig', [
            'controller_name' => 'HomeController',
            'croisiere' => $croisiere,
            'pays' => $pay,

        ]);
    }

     /**
     * @Route("/reserver/croisiere/{id}", name="app_reserver_croisiere", methods={"GET", "POST"})
     */
    public function Reserver(CroisiereRepository $croisiereRepository ,string $id,Request $request, ClientRepository $clientRepository): Response
    {
        $croisiere = $croisiereRepository->findById($id);
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->add($client);
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);

        }

      
        return $this->render('Client/croisiere/reserver.html.twig', [
            'controller_name' => 'HomeController',
            'croisiere' => $croisiere,
            'form' => $form->createView()

        ]);
    }
}