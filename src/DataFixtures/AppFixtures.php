<?php

namespace App\DataFixtures;

use App\Entity\Demandestage;
use App\Entity\Department;
use App\Entity\Document;
use App\Entity\Stage;
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

        $etudiant2 = (new User())
            ->setEmail('etudiant2@tek-up.tn')
            ->setRole('ETUDIANT');
        $etudiant2->setPassword($this->hasher->hashPassword($etudiant2, 'etudiant'));
        $em->persist($etudiant2);

        $depInfo = (new Department())
            ->setNom('Informatique')
            ->setDescription('Département Informatique');
        $em->persist($depInfo);

        $depReseau = (new Department())
            ->setNom('Réseaux')
            ->setDescription('Département Réseaux');
        $em->persist($depReseau);

        $s1 = (new Stage())
            ->setTitre('Stage PFE Symfony')
            ->setTypestage('PFE')
            ->setDescription('Développement d\'une application Symfony')
            ->setDuree(6)
            ->setDepartment($depInfo)
            ->setEncadrant($encadrant);
        $em->persist($s1);

        $s2 = (new Stage())
            ->setTitre('Stage été réseaux')
            ->setTypestage('Été')
            ->setDescription('Mise en place et supervision réseau')
            ->setDuree(2)
            ->setDepartment($depReseau)
            ->setEncadrant($encadrant);
        $em->persist($s2);

        $d1 = (new Demandestage())
            ->setEtudiant($etudiant)
            ->setStage($s1)
            ->setStatut('En attente')
            ->setDatedemande(new \DateTime('now'));
        $em->persist($d1);

        $d2 = (new Demandestage())
            ->setEtudiant($etudiant2)
            ->setStage($s2)
            ->setStatut('Acceptée')
            ->setDatedemande(new \DateTime('-3 days'));
        $d2->setAvisEncadrant('Bon profil, motivation élevée.');
        $d2->setDateDecision(new \DateTimeImmutable('-2 days'));
        $d2->setNote(16);
        $d2->setAppreciation('Très bon travail pendant le stage.');
        $em->persist($d2);

        $doc = (new Document())
            ->setType('Rapport')
            ->setFichier('rapport_demo.pdf')
            ->setDateupload(new \DateTime('now'))
            ->setDemandestage($d2)
            ->setStatutValidation('En attente');
        $em->persist($doc);

        $em->flush();
    }
}