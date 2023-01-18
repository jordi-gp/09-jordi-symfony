<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    # Per a poder fer les migracions i utilitzar les bases de dades
    # cal executar en el terminal el comandament:
    # 'sudo docker exec -it 2023-truiter-symfony_web-server_1 /bin/bash'
    # si es treballa en l'equip local de casa el nom del servici es 2023-truiter-symfony-web-server-1

    # Per a comprovar el nom dels contenidors de docker gastar comandament:
    # 'sudo docker ps'

    #[Route('/home', name: 'index')]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        $usuarios = $usuarioRepository->findOneBy([], ['username' => 'ASC']);

        return $this->render('default.html.twig', [
            'usuario' => $usuarios,
        ]);
    }
}
