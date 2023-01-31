<?php

namespace App\Controller;

use App\Entity\Artista;
use App\Entity\Usuario;
use App\Entity\Vinilo;
use App\Form\UsuarioType;
use App\Form\ViniloType;
use App\Repository\ArtistaRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ViniloRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
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

    #[Route('/backoffice/users', name: 'gestioUsuaris')]
    public function gestioUsuaris(UsuarioRepository $usuarioRepository): Response
    {
        $usuaris = $usuarioRepository->findAll();

        return $this->render('backoffice/_gestio_usuaris.html.twig', [
            'controller_name' => 'BackofficeController',
            'usuaris' => $usuaris
        ]);
    }

    #[Route('backoffice/vinils/new', name: 'nuevoVinilo')]
    public function newVinilo(Request $request, ArtistaRepository $artistaRepository, ViniloRepository $viniloRepository): Response
    {
        $vinilo = new Vinilo();

        $artistas[] = $artistaRepository->findAll();

        $form = $this->createForm(ViniloType::class, $vinilo);

        $form->handleRequest($request);

        $vinilo->setCreatedAt(new DateTime());

        if($form->isSubmitted() && $form->isValid()) {
            $vinilo->setRating(0);

            $viniloRepository->save($vinilo, true);

            return $this->redirectToRoute('index');
        }

        return $this->renderForm('backoffice/_new_vinil.html.twig', [
            'form' => $form,
            'artistas' => $artistas
        ]);
    }

    #[Route('/backoffice/users/new', name: 'nuevoUsuario')]
    public function newUsuario(Request $request, UsuarioRepository $usuarioRepository,
                               UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $usuario = new Usuario();

        $roles = [];
        array_push($roles, 'ROLE_USER', 'ROLE_ADMIN');

        $form = $this->createForm(UsuarioType::class, $usuario);

        $form->handleRequest($request);

        $usuario->setCreatedAt(new DateTime());

        if($form->isSubmitted() && $form->isValid()) {
            $usuario->setPassword(
                $userPasswordHasher->hashPassword(
                    $usuario,
                    $form->get('plainPassword')->getData()
                )
            );

            $usuarioRepository->save($usuario, true);
            return $this->redirectToRoute('index');
        }

        return $this->renderForm('backoffice/_new_user.html.twig', [
            'form' => $form,
            'roles' => $roles
        ]);
    }

    #[Route('/backoffice/users/edit/id={id}', name: 'editUsers')]
    public function editaUsuari(UsuarioRepository $usuarioRepository, Usuario $usuario)
    {
        $user = $usuarioRepository->findOneBy(['id'=> $usuario]);

        return $this->render('backoffice/_edit_usuari.html.twig', [
            'usuario' => $user
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
