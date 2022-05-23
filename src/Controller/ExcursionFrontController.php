<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ExcursionRepository;
use App\Repository\PaysRepository;


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
     * @Route("/packageDetailsexcursion/{id}", name="app_package_Details_excursion")
     */
    public function PackageDetails(ExcursionRepository $excursionRepository,string $id, PaysRepository $paysRepository): Response
    {
        $excursion = $excursionRepository->findById($id);
        $pay = $paysRepository->findAll();
        return $this->render('Client/excursion/PackageDetails.html.twig', [
            'controller_name' => 'HomeController',
            'excursion' => $excursion,
            'pays' => $pay,

        ]);
    }

     /**
     * @Route("/reserver/excursion/{id}", name="app_reserver_excursion")
     */
    public function Reserver(ExcursionRepository $excursionRepository ,string $id): Response
    {
        $excursion = $excursionRepository->findById($id);

        return $this->render('Client/excursion/reserver.html.twig', [
            'controller_name' => 'HomeController',
            'excursion' => $excursion,

        ]);
    }
}
