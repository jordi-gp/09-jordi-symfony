<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackofficeController extends AbstractController
{
    #[Route('/backoffice', name: 'backoffice')]
    public function index(): Response
    {
        return $this->render('backoffice/index.html.twig', [
            'controller_name' => 'BackofficeController',
        ]);
    }

    #[Route('/backoffice/usuaris', name: 'gestioUsuaris')]
    public function gestioUsuaris(UsuarioRepository $usuarioRepository): Response
    {
        $usuaris = $usuarioRepository->findAll();

        return $this->render('backoffice/_gestio_usuaris.html.twig', [
            'controller_name' => 'BackofficeController',
            'usuaris' => $usuaris
        ]);
    }
}
