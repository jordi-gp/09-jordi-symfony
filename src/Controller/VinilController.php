<?php

namespace App\Controller;

use App\Entity\Vinilo;
use App\Repository\ArtistaRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ViniloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;

class VinilController extends AbstractController
{
    #[Route('/vinils/list', name: 'vinil_list')]
    public function listVinils(ViniloRepository $viniloRepository, ArtistaRepository $artistaRepository, $page = 1): Response
    {
        $vinilos = $viniloRepository->findAll();
        $artistas = $artistaRepository->findAll();


        #$paginator = new Paginator();
        /*$vinils = $viniloRepository->findAllPaginated();

        $totalVinilsReturned = $vinils->getIterator()->count();

        $totalVinils = $vinils->count();

        $iterator = $vinils->getIterator();

        $limit = 3;
        $maxPages = ceil($paginator->count() / $limit);
        $thisPage = $page;*/

        return $this->render('vinil/_allVinils.html.twig', [
            'vinilos' => $vinilos,
            'artistas' => $artistas,
            /*'vinils' => $vinils,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage*/
        ]);
    }


    #[Route('/vinil/{id}', name: 'concrete_vinil')]
    public function concreteVinil(Vinilo $vinilo): Response
    {
        return $this->render('vinil/_concreteVinil.html.twig', [
            'vinilo' => $vinilo,
        ]);

        //TODO: Revisar quan el projecte estiga en producció
        /*if(!$vinilo)

        $vinil = $viniloRepository->findOneBy(['id' => $vinilo]);
        dump($vinil);
        return $this->render('vinil/_concreteVinil.html.twig', [
            'vinilo' => $vinil,
        ]);

        /*if(!$vinil)

        {
            $message = "No existeix un vinil amb el id introduït";

            return $this->render('vinil/_vinilNotFound.html.twig.html.twig', [
                'message' => $message
            ]);
        } else {
            return $this->render('vinil/_concreteVinil.html.twig', [
                'vinilo' => $vinilo,
            ]);
        }*/
    }

    # Filtre per trobar els vinils d'un artista en concret
    #[Route('/vinils/artista/{name}', name:'vinil_by_artist', methods: 'GET')]
    public function vinilByArtis(string $name, Request $request, ArtistaRepository $artistaRepository, ViniloRepository $viniloRepository): Response
    {
        $artista = $artistaRepository->findOneBy(['name'=>$name]);

        $vinilos = $viniloRepository->findBy(["artista"=>$artista]);

        if(!$vinilos)
        {
            $errorMessage = "No existeix cap artista amb les credencials donades";

            return $this->render('vinil/_vinilNotFound.html.twig', [
                'message' => $errorMessage
            ]);
        }

        return $this->render('vinil/_vinilByArtist.html.twig', [
            'vinilos' => $vinilos,
            'artista' => $artista

        ]);
    }

    #[Route(path:'/vinils/{id}/save', name:'vinil_save')]
    #[IsGranted('ROLE_USER')]
    public function like(Vinilo $vinilo, UsuarioRepository $usuarioRepository): Response
    {
        $user = $this->getUser();
        if(!$vinilo->getLinkingUsers()->contains($user)) {
            $user->addSavedVinil($vinilo);
            $usuarioRepository->save($user, true);
        }

        return $this->redirectToRoute('index');
    }

    #[Route(path: '/usuario/{username}/vinils/saved', name: 'saved_vinils')]
    #[IsGranted('ROLE_USER')]
    public function savedVinils(string $username, UsuarioRepository $usuarioRepository, ViniloRepository $viniloRepository): Response
    {
        $message = "Vinils guardats de l'usuari $username";

        $user = $usuarioRepository->findOneBy(["username"=>$username]);

        $vinils = $user->getSavedVinils()->getValues();

        #dump($vinils);
        return $this->render('usuario/_saved_vinils.html.twig', [
            'message' => $message,
            'vinilos' => $vinils
        ]);
    }

    #[Route(path: '/vinils/{id}/remove', name:'vinil_remove')]
    #[IsGranted('ROLE_USER')]
    public function remove(Vinilo $vinilo, UsuarioRepository $usuarioRepository): Response
    {
        $user = $this->getUser();
        if($vinilo->getLinkingUsers()->contains($user)) {
            $user->removeSavedVinil($vinilo);
            $usuarioRepository->save($user, true);
        }

        return $this->redirectToRoute('index');
    }
}
