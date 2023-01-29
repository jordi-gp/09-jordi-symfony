<?php

namespace App\Controller;

use App\Entity\Usuario;
#use App\Form\RegisterType;
use App\Repository\UsuarioRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function profile(Request $request): Response
    {
        $session = $request->getSession();
        dump($session->get('app.user'));

        return $this->render('usuario/_profile.html.twig');
    }
}
