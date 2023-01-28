<?php

namespace App\DataFixtures;

use App\Entity\Artista;
use App\Entity\Discografica;
use App\Entity\Genero;
use App\Entity\Usuario;
use App\Entity\Vinilo;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        #Usuario
        $usuario = new Usuario();
        $usuario->setName("Jordi");
        $usuario->setUsername("jogapo");
        $usuario->setEmail("jordigar00@gmail.com");
        $plainPassword = "12341234";
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $plainPassword);
        $usuario->setPassword($hashedPassword);
        $usuario->setRole("ROLE_ADMIN");
        $usuario->setCreatedAt(new DateTime());

        $manager->persist($usuario);
        $manager->flush();

        #Discografica
        $discografica = new Discografica();
        $discografica->setName('Patrick Records');

        $manager->persist($discografica);
        $manager->flush();

        #Genere
        $genero = new Genero();
        $genero->setName('Post-Punk');

        $manager->persist($genero);
        $manager->flush();

        #Artista
        $artista = new Artista();
        $artista->setName('Cicatriz');
        $artista->setDescription('Grup punk dels anys 80.');
        $artista->setPhoto('patrick.jpg');
        $artista->setDiscografica($discografica);

        $manager->persist($artista);


        #Artista 2
        $artista2 = new Artista();
        $artista2->setName('Depresión Sonora');
        $artista2->setDescription('Grup de post-punk');
        $artista2->setPhoto('patrick.jpg');
        $artista2->setDiscografica($discografica);

        $manager->persist($artista2);

        $artista3 = new Artista();
        $artista3->setName('El Pau');
        $artista3->setDescription('Cantautor català');
        $artista3->setPhoto('patrick.jpg');
        $artista3->setDiscografica($discografica);

        $manager->persist($artista3);
        $manager->flush();

        /*$vinilo = new Vinilo();
        $vinilo->setName('Default Vinil');
        $vinilo->setCreatedAt(new DateTime());
        $vinilo->setArtista($artista);
        $vinilo->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
        $vinilo->setPrice(99);
        $vinilo->setRating(4.9);
        $vinilo->setCover('patrick.jpg');

        $manager->persist($vinilo);
        $manager->flush();*/

        #Vinilo
        $vinilo = new Vinilo();
        $vinilo->setName('Inadaptados');
        $vinilo->setCreatedAt(new DateTime());
        $vinilo->setArtista($artista);
        $vinilo->setDescription('Primer disc del grup Vasc');
        $vinilo->setPrice(15);
        $vinilo->setRating(4);
        $vinilo->setCover('cicatriz.jpg');

        $manager->persist($vinilo);
        $manager->flush();

        #Vinilo 2
        $vinilo2 = new Vinilo();
        $vinilo2->setName('Depresión Sonora');
        $vinilo2->setCreatedAt(new DateTime());
        $vinilo2->setArtista($artista);
        $vinilo2->setDescription("Albúm homònim i segon treball de l'artista");
        $vinilo2->setPrice(20);
        $vinilo2->setRating(4.5);
        $vinilo2->setCover('depresionSonora.jpg');

        $manager->persist($vinilo2);
        $manager->flush();

        #Vinilo 3
        $vinilo3 = new Vinilo();
        $vinilo3->setName('Antes que llegue el invierno');
        $vinilo3->setCreatedAt(new DateTime());
        $vinilo3->setArtista($artista);
        $vinilo3->setDescription('Segon albúm grabat en estudio per el cantautor');
        $vinilo3->setPrice(20);
        $vinilo3->setRating(3.4);
        $vinilo3->setCover('elPau.jpg');

        $manager->persist($vinilo3);
        $manager->flush();
    }
}
