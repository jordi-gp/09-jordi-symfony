<?php

namespace App\DataFixtures;

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
        $usuario = new Usuario();
        $usuario->setName("Jordi");
        $usuario->setUsername("jogapo");
        $usuario->setEmail("jordigar00@gmail.com");
        $plainPassword = "12341234";
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $plainPassword);
        $usuario->setPassword($hashedPassword);
        $usuario->setRole("USER_ADMIN");
        $usuario->setCreatedAt(new DateTime());

        $manager->persist($usuario);
        $manager->flush();
    }
}
