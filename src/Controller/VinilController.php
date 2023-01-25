<?php

namespace App\Controller;

use App\Entity\Vinilo;
use App\Repository\ViniloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VinilController extends AbstractController
{
    #[Route('/vinils/list', name: 'vinil_list')]
    public function listVinils(ViniloRepository $viniloRepository): Response
    {
        $vinilos = $viniloRepository->findAll();


        return $this->render('vinil/_allVinils.html.twig', [
            'vinilos' => $vinilos
        ]);
    }

    #[Route('/vinil/id={id}', name: 'concrete_vinil')]
    public function concreteVinil(ViniloRepository $viniloRepository, Vinilo $vinilo): Response
    {
        $vinil = $viniloRepository->findOneBy(['id' => $vinilo]);
        dump($vinil);
        return $this->render('vinil/_concreteVinil.html.twig', [
            'vinilo' => $vinil,
        ]);

        /*if(!$vinil)
        {
            $message = "No existeix un vinil amb el id introduït";

            return $this->render('vinil/_vinilNotFound.html.twig', [
                'message' => $message
            ]);
        } else {
            return $this->render('vinil/_concreteVinil.html.twig', [
                'vinilo' => $vinil,
            ]);
        }*/
    }
}
