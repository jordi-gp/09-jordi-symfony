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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
    public function gestioUsuaris(PaginatorInterface $paginator, Request $request, UsuarioRepository $usuarioRepository): Response
    {
        $message = "";
        $usuaris = $usuarioRepository->findAll();

        #Llistat dels rols d'usuari
        $roles = [];

        foreach($usuaris as $usuari)
        {
            $roles[] = $usuari->getRole();
        }

        $userRoles = array_unique($roles);

        #Filtre per rol
        $query = $usuarioRepository->getFindAllQuery();
        $userRole = $request->query->get('role');

        if($userRole) {
            $user = $usuarioRepository->findOneBy(['role' => $userRole]);

            if($user) {
                $query = $usuarioRepository->getFindByUserRole($userRole);
                $message = "Usuaris amb rol ". $userRole;
            }
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), #Nombre de la pàgina
            2 #Llímit d'elements per pàgina,
        );

        return $this->render('backoffice/_gestio_usuaris.html.twig', [
            'controller_name' => 'BackofficeController',
            'usuaris' => $pagination,
            'roles' => $userRoles,
            'message' => $message
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

    #[Route('/backoffice/users/edit/id={id}', name: 'editUser')]
    public function editaUsuari(Request $request, UsuarioRepository $usuarioRepository, Usuario $usuario)
    {
        $user = $usuarioRepository->findOneBy(['id'=> $usuario]);

        $form = $this->createFormBuilder($usuario)
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_USER' => 'ROLE_USER'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $usuarioRepository->save($usuario, true);
            $this->addFlash('notice', "Rol d'usuari actualitzat de forma correcta");

            return $this->redirectToRoute('index');
        }

        return $this->renderForm('backoffice/_edit_usuari.html.twig', [
            'usuario' => $user,
            'form' => $form
        ]);
    }

    #[Route('/backoffice/vinils', name: 'gestioVinils')]
    public function gestioVinils(ViniloRepository $viniloRepository): Response
    {
        $vinilos = $viniloRepository->findAll();

        #dump($vinilos);

        return $this->render('backoffice/_gestio_vinils.html.twig', [
            'controller_name' => 'BackOfficeController',
            'vinilos' => $vinilos
        ]);
    }

    #[Route('/backoffice/user/delete', name: 'deleteUser')]
    public function deleteUser(UsuarioRepository $usuarioRepository, Request $request): Response
    {
        #Comprovació de que l'usuari no te vinils guardats
        $idUsuario = $request->get('user');
        $usuario = $usuarioRepository->findOneBy(['id' => $idUsuario]);
        $vinilsGuardats = $usuario->getSavedVinils()->getValues();


        if(!empty($vinilsGuardats)) {
            $this->addFlash('advice', "No es pot esborrar un usuario amb vinils guardats");
            return $this->redirectToRoute('index');
        } else {
            $this->addFlash('notice', "L'usuari ha sigut eliminat de forma correcta");
            return $this->render('edit_usuario/_delete_form.html.twig', [
                    'usuario' => $usuario
                ]
            );
        }
    }
}
