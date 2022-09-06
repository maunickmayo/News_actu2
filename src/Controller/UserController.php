<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    /**
    * @Route("/inscription", name= "user_register", methods={"GET|POST"})
    */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
       $user = New User();
       
       $form = $this->createForm(RegisterFormType::class, $user)
           ->handleRequest($request); 
           
           if($form->isSubmitted() && $form->isValid()) {
               $user->setRoles(['ROLE_USER']);
               $user->setCreatedAt(new DateTime());
               $user->setUpdatedAt(new DateTime());
               //On setUpdatedAt parce que tres svt on va afficher ds l espace perso ou profil la derniere date de modif, donc si c est pas modifié ça sera la meme valeur que  la date de création.
               $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
                               // ici on a pas besoin de getData() vu qu il a ete auto hydraté, c est plus rapide a ecrire.
    
               $entityManager->persist($user);
               $entityManager->flush();
                              // success et le second ; le message.
               $this->addFlash('success', "Vous vous êtes inscrit avec succès !");
               return $this->redirectToRoute('app_login');
           
          }

    return $this->render("user/register.html.twig", [
        'form' => $form->createView()   // ici le scond parametre st envoyé dans le register.html.twig 
        // nb: consequence "clé" => valeur  pour appeller le formulaire et la variable s'appellera 'form' (le nom de la clé).
     ]); 



    }




}
