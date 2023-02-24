<?php

namespace App\Controller;

use App\Entity\Vinilo;
use App\Repository\ArtistaRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ViniloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;

class VinilController extends AbstractController
{
    #[Route('/vinils/list', name: 'vinil_list')]
    public function listVinils(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, ViniloRepository $viniloRepository, ArtistaRepository $artistaRepository): Response
    {
        #Missatge d'aplicació de filtres
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

        #Filtre entre dos rangs
        $dataInici = $request->query->get('dataInici');
        $dataFi = $request->query->get('dataFi');

        if(!$dataInici || !$dataFi)
            $this->createNotFoundException();
        else {
            $query = $viniloRepository->getFindByCreationDate($dataInici, $dataFi);
            $message = "Vinils creats entre ". $dataInici ." y ". $dataFi .".";
        }

        #Paginació
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), #Nombre de la pàgina
            4 #Llímit d'elements per pàgina,
        );

        return $this->render('vinil/_allVinils.html.twig', [
            'vinilos' => $pagination,
            'artistas' => $artistas,
            'message' => $message
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

        $this->addFlash('notice', 'Vinil guardat de forma correcta');

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

        $this->addFlash('warning', 'Vinil eliminat de la col·lecció');

        return $this->redirectToRoute('index');
    }
}
