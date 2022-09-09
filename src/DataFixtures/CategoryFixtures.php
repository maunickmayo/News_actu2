<?php

namespace App\DataFixtures;

use App\Entity\Category;
use DateTime;                                        
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    

        private SluggerInterface $slugger;  // SluggerInterface c est le type se $s lugger

        public function __construct(SluggerInterface $slugger)
        {
           $this->slugger= $slugger;
        }     

        public function load(ObjectManager $manager): void
        {
           $categories = [
            'Politique',
            'société',
            'Sport',
            'Cinema',
            'Santé',
            'Sciences',
            'Musique',
            'Hi-Tech',
            'Eclogie'
        ];
         foreach($categories as $cat) {

             $category = new Category();

             $category ->setName($cat);
             $category ->setAlias($this->slugger->slug($cat)); // alias sert a nettoyer à assainir une string via le Slug.
              // tableau multidimenssionnel cad tableau ds un tableau.

             $category->setCreatedAt(new DateTime());
             $category->setUpdatedAt(new DateTime());

             #la méthode persist() exécute les requetes SQL en BDD.
             $manager->persist($category);
           }

            # La méthode flush() n'est pas dans la boucle foreach() pour une raison :
            # => cette méthode "vide" l'objet $manager (c'est un container).
            $manager->flush();

            //NB: on fait les fixtures parce qu'au debut de notre projet on a besoin de manipuler des données. et on ne les a pas forcements ds la BDD au début.on a besoin de faire un CRUD.

        }
}
 
        /* -----rappels règles syntaxe------
        -les vriables en ($)
        -Les strings entre les cotes('')
        -Les tableaux ([]) 
        -Les classes (Majuscules)
        -les integer (chiffres)
        -les methodes et focntions avec les ()

         NB: en info des qu'il y'a une extension(.) c'est un fichier.

         raccoursis pour rechercher une occurence sur  : ctrl+F (search) | cmd+F sur mac.
         et ctrl+R (search end remplace) : pour la rechercher et la remplacer.
        */