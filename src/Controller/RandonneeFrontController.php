<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RondonneeRepository;
use App\Repository\PaysRepository;
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
     * @Route("/packageDetailsrandonnee/{id}", name="app_package_Details_randonnee", methods={"GET"})
     */
    public function PackageDetails(RondonneeRepository $randonneeRepository,string $id, PaysRepository $paysRepository): Response
    {
        $rondonnee = $randonneeRepository->findById($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/Randonnee/PackageDetails.html.twig', [
            'controller_name' => 'RandonneeFrontController',
            'rondonnee' => $rondonnee,
            'pays' => $pay,

        ]);
    }

     /**
     * @Route("/reserver/rondonnee/{id}", name="app_reserver_randonnee", methods={"GET"})
     */
    public function Reserver(RondonneeRepository $randonneeRepository ,string $id): Response
    {
        $rondonnee = $randonneeRepository->findById($id);

        return $this->render('Client/Randonnee/reserver.html.twig', [
            'controller_name' => 'RandonneeFrontController',
            'rondonnee' => $rondonnee,

        ]);
    }
}