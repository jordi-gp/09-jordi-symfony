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

<<<<<<< HEAD
=======

>>>>>>> 711f66d50d87d7052b14d584439effbe902b913a
        return $this->render('vinil/_allVinils.html.twig', [
            'vinilos' => $vinilos
        ]);
    }

    #[Route('/vinil/{id}', name: 'concrete_vinil')]
    public function concreteVinil(Vinilo $vinilo): Response
    {
<<<<<<< HEAD
        return $this->render('vinil/_concreteVinil.html.twig', [
            'vinilo' => $vinilo,
        ]);

        //TODO: Revisar quan el projecte estiga en producció
        /*if(!$vinilo)
=======
        $vinil = $viniloRepository->findOneBy(['id' => $vinilo]);
        dump($vinil);
        return $this->render('vinil/_concreteVinil.html.twig', [
            'vinilo' => $vinil,
        ]);

        /*if(!$vinil)
>>>>>>> 711f66d50d87d7052b14d584439effbe902b913a
        {
            $message = "No existeix un vinil amb el id introduït";

            return $this->render('vinil/_vinilNotFound.html.twig', [
                'message' => $message
            ]);
        } else {
            return $this->render('vinil/_concreteVinil.html.twig', [
                'vinilo' => $vinilo,
            ]);
        }*/
    }
}
