<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VinilController extends AbstractController
{
    #[Route('/vinil/{name}', name: 'concrete_vinil')]
    public function concrete_vinil(int $id): Response
    {
        $text = "Vinil amb id $id";

        return $this->render('vinil/_concreteVinil.html.twig', [
            'message' => $text,
        ]);
    }
}
