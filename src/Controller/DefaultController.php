<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/home', name: 'index')]
    public function index(): Response
    {
        $message = "Benvingut a la tenda";

        return $this->render('default.html.twig', [
            'message' => $message,
        ]);
    }
}
