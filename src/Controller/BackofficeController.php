<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use App\Repository\ViniloRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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

    #[Route('/backoffice/new/Usuario', name: 'nuevoUsuario')]
    public function newUsuario(Request $request, UsuarioRepository $usuarioRepository): Response
    {
        $usuario = new Usuario();

        $usuario->setCreatedAt(new DateTime());

        $roles = [];
        array_push($roles, 'ROLE_USER', 'ROLE_ADMIN');

        $form = $this->createForm(UsuarioType::class, $usuario);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $usuarioRepository->save($usuario, true);
            return $this->redirectToRoute('index');
        }

        return $this->renderForm('backoffice/_new_user.html.twig', [
            'form' => $form,
            'roles' => $roles
        ]);
    }

    #[Route('/backoffice/vinils', name: 'gestioVinils')]
    public function gestioVinils(ViniloRepository $viniloRepository): Response
    {
        $vinilos = $viniloRepository->findAll();

        dump($vinilos);

        return $this->render('backoffice/_gestio_vinils.html.twig', [
            'controller_name' => 'BackOfficeController',
            'vinilos' => $vinilos
        ]);
    }
}
