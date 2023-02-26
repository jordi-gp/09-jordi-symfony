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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

    #[Route('/backoffice/vinils/edit/id={id}', name: 'editVinil')]
    public function editaVinilo(Request $request, ViniloRepository $viniloRepository, Vinilo $vinilo)
    {
        $vinil = $viniloRepository->findOneBy(['id'=> $vinilo]);

        $form = $this->createForm(ViniloTYpe::class, $vinilo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $viniloRepository->save($vinilo, true);
            $this->addFlash('notice', "Rol d'usuari actualitzat de forma correcta");

            return $this->redirectToRoute('index');
        }

        return $this->renderForm('backoffice/_edit_vinil.html.twig', [
            'vinilo' => $vinilo,
            'form' => $form
        ]);
    }

    #[Route('/backoffice/users', name: 'gestioUsuaris')]
    public function gestioUsuaris(PaginatorInterface $paginator, Request $request, UsuarioRepository $usuarioRepository): Response
    {
        $message = "";
        $usuaris = $usuarioRepository->findAll();
        $query = $usuarioRepository->getFindAllQuery();

        #Llistat dels rols d'usuari
        $roles = [];

        foreach($usuaris as $usuari)
        {
            $roles[] = $usuari->getRole();
        }

        $userRoles = array_unique($roles);

        #Filtre per formulari de búsqueda
        $formQuery = $request->query->get('busqueda');

        if($formQuery) {
            $query = $usuarioRepository->getFindByUsernameQuery($formQuery);
            $message = "Usuaris amb nom '". $formQuery."'";
        }

        #Filtre per rol
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

        $form = $this->createFormBuilder(ViniloType::class, $vinilo);

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
    public function gestioVinils(PaginatorInterface $paginator, Request $request, ArtistaRepository $artistaRepository, ViniloRepository $viniloRepository): Response
    {
        $vinilos = $viniloRepository->findAll();

        #Filtres
        $message = "";

        #Filtre per artista
        $artistas = $artistaRepository->findAll();

        $query = $viniloRepository->getFindAllQuery();

        $artistName = $request->query->get('artista');
        if ($artistName) {
            $artista = $artistaRepository->findOneBy(["name" => $artistName]);

            if($artista) {
                $query = $viniloRepository->getFindByArtistQuery($artista);
                $message = "Vinils de l'artista ".$artista->getName();
            }
        }
        $this->createNotFoundException('Vinil not Found');

        #Filtre per formulari de búsqueda
        $formQuery = $request->query->get('busqueda');

        if($formQuery) {
            $query = $viniloRepository->getFindByVinilNameQuery($formQuery);
            $message = "Vinils amb nom '". $formQuery."'";
        }

        #Paginació
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), #Nombre de la pàgina
            4 #Llímit d'elements per pàgina,
        );

        return $this->render('backoffice/_gestio_vinils.html.twig', [
            'controller_name' => 'BackOfficeController',
            'vinilos' => $pagination,
            'artistas' => $artistas,
            'message' => $message
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

    #[Route('/backoffice/vinil/delete', name: 'deleteVinil')]
    public function deleteVinil(ViniloRepository $viniloRepository, Request $request): Response
    {
        #Comprovació de que l'usuari no te vinils guardats
        $idVinilo = $request->get('id');
        $vinilo = $viniloRepository->findOneBy(['id' => $idVinilo]);

        return $this->render('backoffice/delete_vinil.html.twig', [
                'vinilo' => $vinilo
            ]
        );
    }

    #[Route('/{id}', name: 'app_edit_vinilo_delete', methods: ['POST'])]
    public function delete(Request $request, Vinilo $vinilo, ViniloRepository $viniloRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vinilo->getId(), $request->request->get('_token'))) {
            $viniloRepository->remove($vinilo, true);
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route(path: '/backoffice/{username}/vinils/saved', name: 'saved_vinils_backoffice')]
    #[IsGranted('ROLE_ADMIN')]
    public function savedVinils(string $username, UsuarioRepository $usuarioRepository): Response
    {
        $message = "Vinils guardats de l'usuari $username";

        $user = $usuarioRepository->findOneBy(["username"=>$username]);

        $vinils = $user->getSavedVinils()->getValues();

        dump($vinils);
        return $this->render('/backoffice/savedVinilsByUserBackoffice.html.twig', [
            'message' => $message,
            'vinilos' => $vinils
        ]);
    }
}
