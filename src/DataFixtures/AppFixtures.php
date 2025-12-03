<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $em): void
    {
        // ADMIN
        $admin = (new User())
            ->setEmail('admin@tek-up.tn')
            ->setRole('ADMIN');
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $em->persist($admin);

        // ENCADRANT
        $encadrant = (new User())
            ->setEmail('encadrant@tek-up.tn')
            ->setRole('ENCADRANT');
        $encadrant->setPassword($this->hasher->hashPassword($encadrant, 'encadrant'));
        $em->persist($encadrant);

        // ETUDIANT
        $etudiant = (new User())
            ->setEmail('etudiant@tek-up.tn')
            ->setRole('ETUDIANT');
        $etudiant->setPassword($this->hasher->hashPassword($etudiant, 'etudiant'));
        $em->persist($etudiant);

        $em->flush();
    }
}