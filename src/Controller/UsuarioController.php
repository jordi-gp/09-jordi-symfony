<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\RegisterType;
use App\Repository\UsuarioRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UsuarioRepository $usuarioRepository,
                             UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new Usuario();

        $user->setCreatedAt(new DateTime());
        $user->setRole('ROLE_USER');

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $usuarioRepository->save($user, true);
            return $this->redirectToRoute('index');
        }

        return $this->renderForm('usuario/index.html.twig', [
            'form' => $form,
        ]);
    }
}
