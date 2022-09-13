<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_home", methods={"GET"})
     */
    public function home(EntityManagerInterface $entitymanager): Response
    {
     # exercice : récuperer les articles non archives et envoyer les a la vue twig
     /*
        public function home(): Response 
        {
            return $this->render("default/home.html.twig");
        }
     */ # le repository pour récuperer (il sert a la lecture)     ici c la méthode
     $articles = $entitymanager->getRepository(Article::class)->findBy(['deletedAt' => null]);
        return $this->render("default/home.html.twig", [
            'articles' => $articles
        ]);      
       
    }
}
