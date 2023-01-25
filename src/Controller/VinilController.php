<?php

namespace App\Controller;

use App\Entity\Vinilo;
use App\Repository\ViniloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VinilController extends AbstractController
{
    #[Route('/vinil/id={id}', name: 'concrete_vinil')]
    public function concrete_vinil(ViniloRepository $viniloRepository, Vinilo $vinilo): Response
    {
        $vinil = $viniloRepository->findOneBy(['id' => $vinilo]);

        return $this->render('vinil/_concreteVinil.html.twig', [
            'vinilo' => $vinil,
        ]);
    }
}
