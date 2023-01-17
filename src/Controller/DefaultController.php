<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/home', name: 'index')]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        $message = "Benvingut a la tenda";
        $usuarios = $usuarioRepository->findOneBy([], ['username' => 'DESC']);

        return $this->render('default.html.twig', [
            'message' => $message,
            'usuario' => $usuarios,
        ]);
    }
}
