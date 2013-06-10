<?php
// src/Sdz/BlogBundle/DataFixtures/ORM/Categories.php

namespace Ism\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ism\BlogBundle\Entity\Categorie;

class Categories implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des noms de catégorie à ajouter
    $noms = array('Middleware', 'Système', 'Infrastructure', 'Base de données', 'Bureautique');

    foreach ($noms as $i => $nom) {
      // On crée la catégorie
      $liste_categories[$i] = new Categorie();
      $liste_categories[$i]->setNom($nom);

      // On la persiste
      $manager->persist($liste_categories[$i]);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}
