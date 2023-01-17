<?php

namespace App\DataFixtures;

use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $usuario = new Usuario();
        $usuario->setName("Jordi");
        $usuario->setUsername("jogapo");
        $usuario->setEmail("jordigar00@gmail.com");
        $usuario->setPassword("12341234");
        $usuario->setRole("admin");

        $manager->persist($usuario);
        $manager->flush();
    }
}
