<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    #[Route('/form', name: 'app_form')]
    public function register(): Response
    {
        $user = new Usuario();

        $form = $this->createForm(UsuarioType::class, $user);
        return $this->render('form/index.html.twig', [
            'form' => $form,
        ]);
    }
}
