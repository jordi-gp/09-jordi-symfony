<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\Usuario1Type;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/edit/usuario')]
class EditUsuarioController extends AbstractController
{
    #[Route('/', name: 'app_edit_usuario_index', methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('edit_usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_edit_usuario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UsuarioRepository $usuarioRepository): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(Usuario1Type::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuarioRepository->save($usuario, true);

            return $this->redirectToRoute('app_edit_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('edit_usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_edit_usuario_show', methods: ['GET'])]
    public function show(Usuario $usuario): Response
    {

        return $this->render('edit_usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_edit_usuario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Usuario $usuario, UsuarioRepository $usuarioRepository): Response
    {
        $queryId = $request->get('id');
        $userId = $this->getuser()->getId();

        dump($userId);

        if($queryId != $userId) {
            return $this->redirectToRoute('profile');
        } else {
            $user = $this->getUser();

            $form = $this->createForm(Usuario1Type::class, $usuario);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $usuarioRepository->save($usuario, true);

                return $this->redirectToRoute('app_edit_usuario_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('edit_usuario/edit.html.twig', [
                'usuario' => $usuario,
                'form' => $form,
            ]);
        }
    }

    #[Route('/{id}', name: 'app_edit_usuario_delete', methods: ['POST'])]
    public function delete(Request $request, Usuario $usuario, UsuarioRepository $usuarioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $usuarioRepository->remove($usuario, true);
        }

        return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }
}
